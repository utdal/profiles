<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Spatie\Tags\Tag;

trait HasCachedSearch
{

    /**
     * IDs of public profiles associated with a given tag.
     *
     * @param  Collection  $tags
     * @param string $slug
     * @return int[]
     */
    public static function cachedProfileIdsAssociatedWithTag(Collection $tags, string $slug): array
    {
        // Tagged 'profiles'/'profile_data' so the existing flushes on profile edits, archive and restore clear it too.
        return Cache::tags(['profile_tags', 'profiles', 'profile_data'])
            ->remember("tag-profiles:{$slug}", 86400, fn () => static::query()
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tags->modelKeys()))
                ->public()
                ->excludingUnlisted()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->pluck('id')
                ->all());
    }

    /**
     * IDs of public profiles whose associated tags match the search parameter.
     *
     * @param  string  $search
     * @return int[]
     */
    public static function cachedProfileIdsTaggedWithKeyword(string $search): array
    {
        return Cache::tags(['profile_tags', 'profiles', 'profile_data'])
                ->remember('search-tag:' . md5(mb_strtolower($search)), 86400, fn () => static::taggedWith($search)
                    ->public()
                    ->excludingUnlisted()
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->pluck('id')
                    ->all());
    }

    /**
     * IDs of public profiles whose data contains the search term, sorted by name.
     *
     * Cached for a day under 'profiles'/'profile_data', so the existing flushes on
     * profile edits, create, archive and restore clear it. The key uses the exact
     * term because keyword matching is case-sensitive.
     *
     * @param  bool  $exclude_name_matches
     * @return int[]
     */
    public static function cachedProfileIdsContainingKeyword(string $search, bool $exclude_name_matches = true): array
    {
        $key = 'keyword-ids:' . ($exclude_name_matches ? 'without-names:' : 'all:') . md5($search);

        return Cache::tags(['profiles', 'profile_data'])->remember($key, 86400, fn () => static::containing($search, null, match_encoded: true)
            ->when($exclude_name_matches, fn ($query) => $query->where('full_name', 'NOT LIKE', "%$search%"))
            ->public()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->pluck('id')
            ->all());
    }

    /**
     * All tags of any type with the given slug.
     *
     * Several tags can share a slug when their names differ only by case. Cached for a
     * day under 'profile_tags', so creating, renaming or deleting any tag clears it
     * (see AppServiceProvider). Unknown slugs cache an empty result too.
     */
    public static function cachedTagsWithSlug(string $slug): Collection
    {
        return Cache::tags(['profile_tags'])
            ->remember("tags-with-slug:{$slug}", 86400, fn () => Tag::where('slug->en', $slug)->get());
    }

    /**
     * Paginate an ordered list of profile IDs, loading only the profiles on the current page.
     *
     * @param  int[]  $ids
     */
    public static function paginateIds(array $ids, string $page_name = 'page', int $per_page = 24): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage($page_name);
        $page_ids = array_slice($ids, ($page - 1) * $per_page, $per_page);

        $page_profiles = static::whereIn('id', $page_ids)
            ->with(['information', 'media'])
            ->get()
            ->sortBy(fn ($profile) => array_search($profile->id, $page_ids))
            ->values();

        return new LengthAwarePaginator($page_profiles, count($ids), $per_page, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $page_name,
        ]);
    }
}