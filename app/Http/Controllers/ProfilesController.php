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
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

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

        $this->middleware('can:export,profile')->only('pdfExport');

        $this->middleware('can.create.profile')->only('create');

        $this->middleware('can:viewAdminIndex,App\Profile')->only('table');

        $this->middleware('can:delete,profile')->only([
            'confirmDelete',
            'archive',
        ]);

        $this->middleware('can:restore,profile')->only([
            'confirmRestore',
            'restore',
        ]);
    }

    /**
     * Display a listing of profiles.
     */
    public function index(Request $request): View|ViewContract|RedirectResponse
    {
        $search = $request->input('search');

        /** @var EloquentCollection */
        $profiles = Profile::where('full_name', 'LIKE', "%$search%")->public()->with(['information', 'media'])->paginate(24);

        if ((Cache::get('settings')['profile_search_shortcut'] ?? false) && ($profiles->count() === 1) && ($profiles->first()->full_name === $search)) {
            return redirect()->route('profiles.show', ['profile' => $profiles->first()]);
        }

        $keyword_profiles = !empty($search) ? Profile::containing($search)
            ->where('full_name', 'NOT LIKE', "%$search%")->public()->paginate(24, ['*'], 'key') : collect();

        $tag_profiles = !empty($search) ? Profile::taggedWith($search)->public()->paginate(24, ['*'], 'tag') : null;

        $schools = !empty($search) ? School::withNameLike($search)->get() : collect();

        if ((Cache::get('settings')['school_search_shortcut'] ?? false) && ($schools->count() === 1) && $schools->first()->hasName($search, false, true)) {
            return redirect()->route('schools.show', ['school' => $schools->first()]);
        }

        return view('profiles.index', compact('profiles', 'keyword_profiles', 'tag_profiles', 'schools', 'search'));
    }

    /**
     * Display the home page
     */
    public function home(): View|ViewContract
    {
        $random_profile = Cache::tags(['home', 'profiles'])->remember('home-random-profiles', 86400, function() {
            return Profile::public()->inRandomOrder()->limit(2)->get();
        });

        $num_profiles = Cache::tags(['home', 'profiles'])->remember('home-profile-count', 86400, function() {
            return Profile::public()->count();
        });

        $num_publications = Cache::tags(['home', 'profile_data'])->remember('home-publication-count', 86400, function() {
            return ProfileData::where('type', 'publications')->count();
        });

        $num_datum = Cache::tags(['home', 'profile_data'])->remember('home-datum-count', 86400, function() {
            return ProfileData::count();
        });

        $tags = Cache::tags(['home', 'profile_tags'])->remember('home-tags', 86400, function() {
            return Tag::whereExists(function ($query) {
                $query->select(DB::raw(1))->from('taggables')->whereRaw('tags.id = taggables.tag_id');
            })->whereType(Profile::class)->inRandomOrder()->limit(16)->get();
        });

        return view('home', compact('random_profile', 'num_profiles', 'num_publications', 'num_datum', 'tags'));
    }

    /**
     * Display an admin table of profiles.
     */
    public function table(): View|ViewContract
    {
        return view('profiles.table');
    }

    /**
     * Show the specified profile.
     */
    public function show(Request $request, Profile $profile): View|ViewContract
    {
        /** @var User the logged-in user */
        $user = Auth::user();
        $editable = $user && $user->can('update', $profile);

        //don't show unless profile is public or we can edit it
        if(!$profile->public && !$editable){
            abort(404);
        }

        return view('profiles.show', [
            'profile' => $profile,
            'editable' => $editable,
            'paginated' => $request->boolean('paginated', true),
            'information' => $profile->information->first(),
        ]);
    }

    /**
     * Redirect from ID to slug.
     */
    public function redirectById(int $id): RedirectResponse
    {
        $profile = Profile::findOrFail($id);

        return redirect()->route('profiles.show', $profile);
    }

    /**
     * Create a Profile
     */
    public function create(Request $request, User $user, LdapHelperContract $ldap): View|ViewContract|RedirectResponse
    {
        $existing_profile = $user->profiles()->withTrashed()->first();

        if ($existing_profile?->trashed()) {
            session()->flash('flash_message', "That profile already exists, but is archived.");
            return $this->confirmRestore($request, $existing_profile);
        }

        if ($existing_profile) {
            return redirect()
                ->route('profiles.edit', ['profile' => $existing_profile, 'section' => 'information'])
                ->with('flash_message', 'That profile already exists.');
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

        Cache::tags(['profiles', 'profile_data'])->flush();

        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', 'Profile created.');

    }

    /**
     * Show the view for editing a profile section
     */
    public function edit(Profile $profile, string $section): View|ViewContract|RedirectResponse
    {
        //dont manage auto-managed publications
        if ($section == 'publications' && $profile->hasOrcidManagedPublications()) {
            $profile->updateORCID();
            return redirect()
                ->route('profiles.show', $profile->slug)
                ->with('flash_message', 'Publications updated via ORCID.');
        }

        $data = $profile->data()->$section()->get();

        // if no data, include one item to use as a template
        if ($section != 'information' && $data->isEmpty()) {
            $record = new ProfileData();
            $record->id = 0;
            $record->public = true;
            $data->push($record);
        }

        return view('profiles.edit', compact('profile', 'section', 'data'));
    }

    /**
     * Update a Profile's ORCID
     */
    public function orcid(Profile $profile): RedirectResponse
    {
      if ($profile->updateORCID()) {
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Publications updated via ORCID.');
      } else {
          return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Error updating your ORCID publications.');
      }
    }

    /**
     * Update a Profile
     */
    public function update(Profile $profile, string $section, ProfileUpdateRequest $request): RedirectResponse
    {
        $profile->updateDatum($section, $request);

        return redirect()->route('profiles.show', $profile->slug)->with('flash_message', 'Profile updated.');
    }

    public function updateImage(Profile $profile, ProfileImageRequest $request): RedirectResponse
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('image'), 'images'));
    }

    /**
     * Update a Profile's banner image
     */
    public function updateBanner(Profile $profile, ProfileBannerImageRequest $request): RedirectResponse
    {
        return redirect()->route('profiles.edit', [$profile->slug, 'information'])->with('flash_message', $profile->processImage($request->file('banner_image'), 'banners'));
    }

    /**
     * Confirm deletion of a profile
     */
    public function confirmDelete(Profile $profile): View|ViewContract
    {
        return view('profiles.delete', ['profile' => $profile]);
    }

    /**
     * Confirm restoration of a soft-deleted profile
     */
    public function confirmRestore(Request $request, Profile $profile): View|ViewContract|RedirectResponse
    {
        // this message is in case someone tries to create an already archived profile
        if ($request->user()->cannot('restore', $profile)) {
            return back()->withInput()->with([
                'flash_message' => "The profile {$profile->full_name} is archived. Contact a site admin to restore it.",
                'flash_message_type' => 'danger',
            ]);
        }
    
        return view('profiles.restore', ['profile' => $profile]);
    }

    /**
     * Remove the profile from the database
     */
    public function archive(Profile $profile): RedirectResponse
    {
        $profile->delete();

        Cache::tags(['profiles', 'profile_data'])->flush();

        return redirect()->route('profiles.table')->with('flash_message', 'The profile of ' . $profile->full_name . ' has been archived.');
    }

    /**
     * Restore a soft deleted profile
     */
    public function restore(Profile $profile): RedirectResponse
    {
        $profile->restore();

        Cache::tags(['profiles', 'profile_data'])->flush();

        return redirect()->route('profiles.table')->with('flash_message', 'The profile of ' . $profile->full_name . ' has been restored.');
    }

    /**
     * Generate PDF Export
     */
    public function pdfExport(Profile $profile): Response
    {
        $pdf_content = Browsershot::url("{$profile->url}?paginated=false")
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->margins(30, 15, 30, 15);

        if (config('pdf.node')) {
            $pdf_content = $pdf_content->setNodeBinary(config('pdf.node'));
        }

        if (config('pdf.npm')) {
            $pdf_content = $pdf_content->setNpmBinary(config('pdf.npm'));
        }

        if (config('pdf.modules')) {
            $pdf_content = $pdf_content->setIncludePath(config('pdf.modules'));
        }

        if (config('pdf.chrome')) {
            $pdf_content = $pdf_content->setChromePath(config('pdf.chrome'));
        }

        if (config('pdf.chrome_arguments')) {
            $pdf_content = $pdf_content->addChromiumArguments(config('pdf.chrome_arguments'));
        }

        return response($pdf_content->pdf())
                ->header('Content-Type', 'application/pdf');
    }
}
