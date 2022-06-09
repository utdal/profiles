<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\ProfileData;
use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Contracts\LdapHelperContract;
use App\Http\Requests\ProfileBannerImageRequest;
use App\Http\Requests\ProfileImageRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\School;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProfilesController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth')->only([
            'table',
            'create',
            'edit',
            'update',
            'updateImage',
            'orcid',
        ]);

        $this->middleware('can:update,profile')->only([
            'edit',
            'update',
            'updateImage',
        ]);

        $this->middleware('can.create.profile')->only('create');

        $this->middleware('can:viewAdminIndex,App\Profile')->only('table');

        $this->middleware('can:delete,profile')->only([
            'confirmDelete',
            'archive',
            'restore',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $profiles = Profile::where('full_name', 'LIKE', "%$search%")->public()->paginate(24);

        if ((Cache::get('settings')['profile_search_shortcut'] ?? false) && ($profiles->count() === 1) && ($profiles->first()->full_name === $search)) {
            return redirect()->route('profiles.show', ['profile' => $profiles->first()]);
        }

        $keyword_profiles = Profile::containing($search)
            ->where('full_name', 'NOT LIKE', "%$search%")->public()->paginate(24, ['*'], 'key');

        $tag_profiles = !empty($search) ? Profile::taggedWith($search)->public()->paginate(24, ['*'], 'tag') : null;

        $schools = !empty($search) ? School::withNameLike($search)->get() : collect();

        if ((Cache::get('settings')['school_search_shortcut'] ?? false) && ($schools->count() === 1) && $schools->first()->hasName($search, false, true)) {
            return redirect()->route('schools.show', ['school' => $schools->first()]);
        }

        return view('profiles.index', compact('profiles', 'keyword_profiles', 'tag_profiles', 'schools', 'search'));
    }

    /**
     * Display the home page
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $random_profile = Cache::remember('home-random-profiles', 86400, function(){
            $profiles = Profile::public()->get();
            return !$profiles->isEmpty() ? $profiles->random(min(2, $profiles->count())) : collect([]);
        }); 

        $num_profiles = Cache::remember('home-profile-count', 86400, function(){
            return Profile::public()->get()->count();
        });

        $num_publications = Cache::remember('home-publication-count', 86400, function(){
            return ProfileData::all()->where('type', 'publications')->count();
        });

        $num_datum = Cache::remember('home-datum-count', 86400, function(){
            return ProfileData::all()->count();
        });

        $tags = Cache::remember('home-tags', 86400, function(){
            $tags = Tag::whereExists(function ($query) {
                $query->select(DB::raw(1))->from('taggables')->whereRaw('tags.id = taggables.tag_id');
            })->inRandomOrder()->get();
            return !$tags->isEmpty() ? $tags->random(min(20, $tags->count())) : collect([]);
        });

        return view('home', compact('random_profile', 'num_profiles', 'num_publications', 'num_datum', 'tags'));
    }

    /**
     * Display an admin table of profiles.
     *
     * @return \Illuminate\View\View
     */
    public function table(Request $request)
    {
        return view('profiles.table', [
            'profiles' => Profile::where('full_name', 'LIKE', "%{$request->profile_search}%")->orderBy('last_name')->paginate(50),
            'profile_search' => $request->profile_search,
        ]);
    }

    /**
     * Show the profile.
     *
     * @param  User   $user
     * @return \Illuminate\View\View
     */
    public function show(Profile $profile)
    {
        /** @var User the logged-in user */
        $user = Auth::user();
        $editable = $user && $user->can('update', $profile);

        //don't show unless profile is public or we can edit it
        if(!$profile->public && !$editable){
            abort(404);
        }

        $information = $profile->data()->information()->first();
        $preparations = $profile->data()->preparation()->get();
        $research_areas = $profile->data()->areas()->get();
        $publications = $profile->data()->publications()->paginate(10, ['*'], 'pub');
        $appointments = $profile->data()->appointments()->paginate(10, ['*'], 'appt');
        $awards = $profile->data()->awards()->paginate(10, ['*'], 'awd');
        $activites = $profile->data()->activities()->get();
        $support = $profile->data()->support()->paginate(5, ['*'], 'sppt');
        $news = $profile->data()->news()->public()->paginate(5, ['*'], 'news');
        $projects = $profile->data()->projects()->paginate(5, ['*'], 'proj');
        $presentations = $profile->data()->presentations()->paginate(5, ['*'], 'pres');
        $affiliations = $profile->data()->affiliations()->paginate(10, ['*'], 'affl');
        $additionals = $profile->data()->additionals()->paginate(3, ['*'], 'addl');

        return view('profiles.show', compact('profile', 'editable', 'information', 'preparations', 'publications', 'research_areas', 'activites', 'support', 'appointments', 'awards', 'news', 'projects', 'presentations', 'affiliations', 'additionals'));
    }

    /**
     * Redirect from ID to slug.
     *
     * @param  User   $user
     *
     */
    public function redirectById($id)
    {
        $profile = Profile::findOrFail($id);

        return redirect()->route('profiles.show', $profile);
    }

    /**
     * Create a Profile
     *
     * @param User $user
     * @param LdapHelperContract $ldap
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(User $user, LdapHelperContract $ldap)
    {
        //redirect to edit page if user already has a profile
        if($user->profiles()->count() > 0){
            return redirect()->route('profiles.edit', [$user->profiles()->first()->slug, 'information'])->with('flash_message', 'Profile already exists.');
        }

        //get fresh information for creating profile stub
        $ldap_user = $ldap->search($user->name, [
            $ldap->schema->loginName(),
            $ldap->schema->displayName(),
            $ldap->schema->department(),
            $ldap->schema->title(),
            $ldap->schema->email(),
            $ldap->schema->telephone(),
            $ldap->schema->physicalDeliveryOfficeName(),
        ])->first();

        //create profile
        $profile = Profile::create([
            'full_name' => $user->display_name,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'slug' => $user->pea,
            'public' => true
        ]);

        $user->profiles()->save($profile);

        //create profile information stub
        $information = ProfileData::create([
            'type' => 'information',
            'data' => [
                'title' => $ldap_user->getTitle() . " - " . $ldap_user->getDepartment(),
                'secondary_title' => '',
                'tertiary_title' => '',
                'email' => $ldap_user->getEmail(),
                'phone' => $ldap_user->getTelephoneNumber(),
                'location' => $ldap_user->getPhysicalDeliveryOfficeName(),
            ],
        ]);

        $profile->data()->save($information);

        Cache::flush();

        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', 'Profile created.');

    }

    /**
     * Show the view for editing a profile section
     *
     * @param Profile $profile
     * @param string $section
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Profile $profile, $section)
    {

        //dont manage auto-managed publications
        if($section == 'publications' && $profile->hasOrcidManagedPublications()){
            $profile->updateORCID();
            return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Publications updated via ORCID.');
        }


        $data = $profile->data()->$section()->get();

        if($section != 'information'){
            for($i = -1; $i > -10; $i--){
                $record = new ProfileData;
                $record->id = $i;
                $data->add($record);
            }
        }

        return view('profiles.edit', compact('profile', 'section', 'data'));
    }

    /**
     * Update a Profile's ORCID
     *
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orcid(Profile $profile)
    {
      if ($profile->updateORCID()) {
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Publications updated via ORCID.');
      } else {
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Error updating your ORCID publications.');
      }
    }

    /**
     * Update a Profile
     *
     * @param Profile $profile
     * @param string $section
     * @param ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Profile $profile, $section, ProfileUpdateRequest $request)
    {
        $profile->updateDatum($section, $request);

        return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Profile updated.');
    }

    public function updateImage(Profile $profile, ProfileImageRequest $request)
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('image'), 'images'));
    }

    /**
     * Update a Profile's banner image
     *
     * @param Profile $profile
     * @param ProfileBannerImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBanner(Profile $profile, ProfileBannerImageRequest $request)
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('banner_image'), 'banners'));
    }

    /**
     * Confirm deletion of a profile
     *
     * @param  Profile $profile
     * @return \Illuminate\View\View
     */
    public function confirmDelete(Profile $profile)
    {
        return view('profiles.delete', compact('profile'));
    }

    /**
     * Remove the profile from the database
     * 
     * @param  Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(Profile $profile)
    {
        $profile->delete();

        return redirect()->route('profiles.table')->with('flash_message', 'The profile of ' . $profile->full_name . ' has been archived.');
    }

    /**
     * Restore a soft deleted profile
     *
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Profile $profile)
    {
        $profile->restore();

        return redirect()->route('profiles.table')->with('flash_message', 'The profile of ' . $profile->full_name . ' has been restored.');
    } 
}
