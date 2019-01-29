<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\ProfileData;
use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Contracts\LdapHelperContract;
use Illuminate\Support\Facades\Cache;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $profiles = Profile::where('full_name', 'LIKE', "%$search%")->public()->paginate(24);

        $keyword_profiles = Profile::containing($search)
            ->where('full_name', 'NOT LIKE', "%$search%")->public()->paginate(24, ['*'], 'key');

        if(!empty($search)){
            $tag_profiles = Profile::taggedWith($search)->public()->paginate(24, ['*'], 'tag');
        }

        return view('profiles.index', compact('profiles', 'keyword_profiles', 'tag_profiles', 'search'));
    }

    public function home(){

        $random_profile = Cache::remember('home-random-profiles', 1440, function(){
            $profiles = Profile::public()->get();
            return !$profiles->isEmpty() ? $profiles->random(min(2, $profiles->count())) : collect([]);
        }); 

        $num_profiles = Cache::remember('home-profile-count', 1440, function(){
            return Profile::public()->get()->count();
        });

        $num_publications = Cache::remember('home-publication-count', 1440, function(){
            return ProfileData::all()->where('type', 'publications')->count();
        });

        $num_datum = Cache::remember('home-datum-count', 1440, function(){
            return ProfileData::all()->count();
        });

        $tags = Cache::remember('home-tags', 1440, function(){
            $tags = Tag::whereExists(function ($query) {
                $query->select(\DB::raw(1))->from('taggables')->whereRaw('tags.id = taggables.tag_id');
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
        $search = $request->input('search');
        $profiles = Profile::where('full_name', 'LIKE', "%$search%")->orderBy('last_name')->paginate(50);

        return view('profiles.table', compact('profiles', 'search'));
    }

    /**
     * Show the user info.
     *
     * @param  User   $user
     * @return \Illuminate\View\View
     */
    public function show(Profile $profile)
    {

        $user = Auth::user();
        $editable = $user && $user->can('update', $profile);

        //don't show unless profile is public or we can edit it
        if(!$profile->public && !$editable){
            return response()->view('errors.404', [], 404);
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
    public function redirectById($id){
        $profile = Profile::findOrFail($id);

        return redirect()->route('profiles.show', $profile);
    }

    public function create(User $user, LdapHelperContract $ldap){

        //redirect to edit page if user already has a profile
        if($user->profiles()->count() > 0){
            return redirect()->route('profiles.edit', [$user->profiles()->first()->slug, 'information'])->with('flash_message', 'Profile already exists.');
        }

        //get fresh information for creating profile stub
        $ldap_user = $ldap->search($user->name, [
            'uid',
            'displayname',
            'title',
            'dept',
            'office',
            'telephonenumber',
            'canonicalmailaddress'
        ]);

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
            'data' => array(
                'title' => $ldap_user[0]['title'] . " - " . $ldap_user[0]['dept'],
                'secondary_title' => '',
                'tertiary_title' => '',
                'email' => $ldap_user[0]['canonicalmailaddress'],
                'phone' => isset($ldap_user[0]['telephonenumber']) ? $ldap_user[0]['telephonenumber'] : NULL,
                'location' => isset($ldap_user[0]['office']) ? $ldap_user[0]['office'] : NULL,
            )
        ]);

        $profile->data()->save($information);

        Cache::flush();

        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', 'Profile created.');

    }

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

    public function orcid(Profile $profile)
    {
      if($profile->updateORCID()){
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Publications updated via ORCID.');
      }else{
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Error updating your ORCID publications.');
      }
    }

    public function update(Profile $profile, $section, Request $request)
    {
        $profile->updateDatum($section, $request);
        return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Profile updated.');
    }

    public function updateImage(Profile $profile, Request $request)
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('image'), 'images'));
    }

    public function updateBanner(Profile $profile, Request $request)
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('image'), 'banners'));
    }
}
