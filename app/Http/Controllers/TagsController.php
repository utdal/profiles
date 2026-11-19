<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Tags\Tag;
use App\Http\Requests\TagsUpdateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Profile;
use Illuminate\Http\JsonResponse;

class TagsController extends Controller
{
    /**
     * Controller constructor.
     * Middleware is applied here.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);

        $this->middleware('can:viewAdminIndex,'.Tag::class)->only('table');

        $this->middleware('can:create,'.Tag::class)->only([
            'create',
            'store',
        ]);

        $this->middleware('can:update,tag,'.Tag::class)->only([
            'editTag',
            'updateTag',
        ]);

    }

    /**
     * Show the index of all associated tags.
     */
    public function index(): View|ViewFactory
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
     */
    public function table(): View|ViewFactory
    {
        return view('tags.table');
    }

    /**
     * Show the public profiles associated with a tag.
     *
     * When no profile has the tag (e.g. a student research topic), fall back to
     * profiles whose data mentions the tag name, like the keyword search.
     */
    public function show(string $slug): View|ViewFactory
    {
        $all_tags = Profile::cachedTagsWithSlug($slug);

        abort_if($all_tags->isEmpty(), 404);

        $tags = $all_tags->where('type', Profile::class);
        $tag = $tags->first() ?? $all_tags->first();

        // Tagged 'profiles'/'profile_data' so the existing flushes on profile edits, archive and restore clear it too.
        $profile_ids = Cache::tags(['profile_tags', 'profiles', 'profile_data'])
            ->remember("tag-profiles:{$slug}", 86400, fn () => Profile::query()
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tags->modelKeys()))
                ->public()
                ->excludingUnlisted()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->pluck('id')
                ->all());

        $keyword_fallback = empty($profile_ids);

        if ($keyword_fallback) {
            $profile_ids = Profile::cachedKeywordIds($tag->name, exclude_name_matches: false);
        }

        $profiles = Profile::paginateIds($profile_ids);

        return view('tags.show', compact('tag', 'profiles', 'keyword_fallback'));
    }

    /**
     * Show the index table of all associated tags.
     */
    public function create(): View|ViewFactory
    {
        return view('tags.create');
    }

    /**
     * Save the tag in the database.
     */
    public function store(TagUpdateRequest $request): RedirectResponse
    {
        Tag::findOrCreate($request->name, $request->type);

        return redirect()->route('tags.table')
            ->with('flash_message', 'Added tags');
    }

    /**
     * Search for Tags.
     */
    public function search(Request $request): JsonResponse
    {
        $name = '%' . strtolower($request->input('name')) . '%';

        $tags = Tag::whereRaw("LOWER(name) like ?", [$name])->select('name')->get();

        return response()->json($tags->pluck('name')->all());
    }

    /**
     * Update the tags on a model.
     */
    public function update(TagsUpdateRequest $request): RedirectResponse|JsonResponse
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

    /**
     * Edit a tag record.
     */
    public function editTag(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update a single tag record.
     */
    public function updateTag(Tag $tag, TagUpdateRequest $request) 
    {
        $tag->update($request->all());

        return redirect()
                ->route('tags.table')
                ->with('flash_message', 'The tag has been updated.');
    }
}
