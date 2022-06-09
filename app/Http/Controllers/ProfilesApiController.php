<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfilesApiController extends Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // CORS middleware is auto-applied to all API routes
    }

    /**
     * Get a listing of all Profiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return Cache::remember($request->fullUrl(), 3600, function() use ($request) {
            $profile = Profile::select(Profile::apiAttributes());

            if ($request->filled('person')) {
                $profile = $profile->whereIn('slug', explode(';', $request->person));
            }

            if ($request->filled('search')) {
                $profile = $profile->containing($request->search, $request->search_section);
            }

            if ($request->filled('search_names')) {
                $profile = $profile->withName($request->search_names);
            }

            if ($request->filled('info_contains')) {
                $profile = $profile->containing($request->info_contains, 'information');
            }

            if ($request->filled('from_school')) {
                $profile = $profile->fromSchool(explode(';', $request->from_school));
            }

            if ($request->filled('tag')) {
                $profile = $profile->withAnyTags(explode(';', $request->tag), Profile::class);
            }

            if ($request->boolean('with_data')) {
                if(count(array_filter($request->query())) <=1){
                    return response()->json(['error' => 'Please use a filter when pulling data.'], 400);
                }
                $profile = $profile->withApiData($request->input('data_type'));
            }

            $profile = $profile->get();
            $count = $profile->count();

            // iterate over all data and strip tags
            if ($request->boolean('with_data') && !$request->boolean('raw_data')){
                $profile->map(function($single_profile) {
                    $single_profile->stripTagsFromData(['publications'], 'title', true);
                    $single_profile->stripTagsFromData([
                        'news',
                        'areas',
                        'projects',
                        'affiliations',
                        'activities',
                        'additionals',
                        'presentations',
                    ], 'description', true);
                });
            }

            return response()->json(compact('count', 'profile'), empty($profile) ? 404 : 200);
        });
    }

    /**
     * Get a Profile with it's data.
     *
     * @param  Profile $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Profile $profile)
    {
        $profile->loadApiData();

        return response()->json(compact('profile'));
    }

    public function authRouteAPI(Request $request)
    {
        return $request->user();
    }
}
