<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Tags\Tag;

class TagsController extends Controller
{
    /**
     * Controller constructor.
     * Middleware is applied here.
     */
    public function __construct()
    {
        $this->middleware('auth',
            ['except' => [
                'index'
            ]]
        );
    }

    /**
     * Show the index of all associated tags.
     *
     * @return \Illuminate\Http\Response
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
     * Search for Tags.
     * 
     * @param  TagSearchRequest $request
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
     * @param  TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $model = null;
        $view = '';
        $message = 'ERROR: Cannot update tags.';
        $modelname = $request->model;

        if ($model = $modelname::find($request->id)) {
            $model->syncTagsWithType($request->tags, $modelname);
            $message = "Tags updated.";
            $view = view('tags.badge', ['tags' => $model->tags()->get()])->render();
        }

        Cache::flush();

        if ($request->ajax()) {
            return response()->json(compact('message', 'view'), ($model) ? 200 : 500);
        }

        return back()->with('flash_message', $message);
    }
}
