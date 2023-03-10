<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Tags\Tag;
use App\Http\Requests\TagsUpdateRequest;

class TagsController extends Controller
{
    /**
     * Controller constructor.
     * Middleware is applied here.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');

        $this->middleware('can:viewAdminIndex,'.Tag::class)->only('table');

        $this->middleware('can:create,'.Tag::class)->only([
            'create',
            'store',
        ]);
    }

    /**
     * Show the index of all associated tags.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function index()
    {
        $tags = Tag::whereExists(function($query) {
            $query->select(\DB::raw(1))->from('taggables')->whereRaw('tags.id = taggables.tag_id');
        })->orderBy('name->en')->get();

        $tag_groups = $tags->groupBy(function($tag, $key) {
            return strtoupper($tag->name[0]);
        })->sortBy(function($tag, $key) {
            return $key;
        });

        return view('tags.index', compact('tag_groups'));
    }

    /**
     * Show the index table of all associated tags.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function table()
    {
        return view('tags.table');
    }

    /**
     * Show the index table of all associated tags.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Save the tag in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Tag::findOrCreate(preg_split('/\r\n|\r|\n/', $request->tag_name ?? ''), $request->tag_type);

        return redirect()->route('tags.table')
            ->with('flash_message', 'Added tags');
    }

    /**
     * Search for Tags.
     *
     * @param  Request $request
     * @return array of tag names
     */
    public function search(Request $request)
    {
        $name = '%' . strtolower($request->input('name')) . '%';

        return Tag::whereRaw("LOWER(name) like ?", [$name])->select('name')->get()->pluck('name');
    }

    /**
     * Update the tags on a model.
     *
     * @param  TagsUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(TagsUpdateRequest $request)
    {
        $model = null;
        $view = '';
        $message = 'ERROR: Cannot update tags.';
        $modelname = $request->model;

        if ($model = $modelname::find($request->id)) {
            $model->syncTagsWithType($request->tags ?? [], $modelname);
            $message = "Tags updated.";
            $view = view('tags.badge', ['tags' => $model->tags()->get()])->render();
        }

        Cache::tags(['profile_tags'])->flush();

        if ($request->ajax()) {
            return response()->json(compact('message', 'view'), ($model) ? 200 : 500);
        }

        return back()->with('flash_message', $message);
    }
}
