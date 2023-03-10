<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{

     /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'can:update,App\Setting']);
    }

    /**
     * Show the settings for editing.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function edit()
    {
        return view('settings', [
            'settings' => Setting::pluck('value', 'name')->toArray(),
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->input('setting') as $name => $value) {
            Setting::updateOrCreate([
                'name' => $name
            ],[
                'value' => is_array($value) ? json_encode($value) : $value
            ]);
        }

        Cache::forget('settings');

        return redirect()->route('app.settings.edit')->with('flash_message', 'Settings updated.');
    }

    /**
     * Update a setting image
     *
     * @param Request $request
     * @param string $setting_name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(Request $request, $setting_name)
    {
        if ($request->hasFile($setting_name)) {
            $setting = Setting::firstOrCreate(['name' => $setting_name]);
            $setting->addMedia($request->file($setting_name))->toMediaCollection($setting_name);
            // $message = $setting->processImage($request->file($image_name), 'settings');
            // $url = $setting->getFullImageUrlAttribute();

            $setting->value = url($setting->getFirstMediaUrl($setting_name) ?: '/img/default.png');

            $setting->save();
            $message = 'Settings image has been updated.';
        } else {
            $message = 'Cannot update settings image.';
        }

        Cache::forget('settings');

        return redirect()->route('app.settings.edit')->with('flash_message', $message);
    }

}
