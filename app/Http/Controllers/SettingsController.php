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
        $this->middleware('auth');

        $this->middleware('can:viewAdminIndex,App\User');

    }
    
    /**
     * Show the settings for editing.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(){
        $settings = Setting::pluck('value', 'name')->toArray();
        return view('settings', compact('settings'));
    }

    public function update(Request $request){

        foreach($request->input('setting') as $name => $value){  
            Setting::updateOrCreate([
                'name' => $name
            ],[
                'value' => $value
            ]);
        }

        Cache::forget('settings');

        return redirect()->route('app.settings.edit')->with('flash_message', 'Settings updated.');
    }

    public function updateImage(Request $request, $image)
    {
        $setting = Setting::firstOrCreate(['name' => $image]);
        $message = $setting->processImage($request->file('image'), 'settings');
        $url = $setting->getFullImageUrlAttribute();

        $setting->value = $url;
        $setting->save();

        Cache::forget('settings');

        return redirect()->route('app.settings.edit')->with('flash_message', $message);
    }

}
