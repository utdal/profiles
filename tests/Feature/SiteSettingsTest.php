<?php

namespace Tests\Feature;

use App\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\Feature\Traits\HasUploadedImage;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * @group settings
 */
class SiteSettingsTest extends TestCase
{
    use HasUploadedImage;
    use LoginWithRole;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test editing site settings.
     *
     * @return void
     */
    public function testEditSiteSettings()
    {
        $this->seed();
        $this->loginAsAdmin();
        Cache::flush();

        $this->get(route('app.settings.edit'))
            ->assertStatus(200)
            ->assertViewIs('settings');
        
        $settings = [
            'primary_color' => $this->faker->hexColor,
            'secondary_color' => $this->faker->hexColor,
            'tertiary_color' => $this->faker->hexColor,
            'site_title' => $this->faker->company,
            'description' => $this->faker->paragraph(),
            'account_name' => 'MyInstititionID',
            'forgot_password_url' => $this->faker->url,
            'school_search_shortcut' => $this->faker->boolean(),
            'profile_search_shortcut' => $this->faker->boolean(),
            'faq' => $this->faker->paragraph(),
            'footer' => $this->faker->paragraph(),
        ];

        $this->followingRedirects()->post(route('app.settings.update'), [
            'setting' => $settings,
        ])
            ->assertStatus(200)
            ->assertViewIs('settings')
            ->assertSee('Settings updated.')
            ->assertSee($settings['primary_color'])
            ->assertSee($settings['secondary_color'])
            ->assertSee($settings['tertiary_color'])
            ->assertSee(e($settings['site_title']))
            ->assertSee(e($settings['description']))
            ->assertSee($settings['account_name'])
            ->assertSee($settings['forgot_password_url'])
            ->assertSee("id=\"setting[school_search_shortcut]\" value=\"1\" " . ($settings['school_search_shortcut'] ? ' checked ' : ''))
            ->assertSee("id=\"setting[profile_search_shortcut]\" value=\"1\" " . ($settings['profile_search_shortcut'] ? ' checked ' : ''))
            ->assertSee(e($settings['faq']))
            ->assertSee(e($settings['footer']));

        foreach ($settings as $setting_name => $setting_value) {
            $this->assertDatabaseHas('settings', [
                'name' => $setting_name,
                'value' => $setting_value,
            ]);
        }

        Auth::logout();

        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee($settings['forgot_password_url'])
            ->assertSee($settings['account_name']);

        $this->get('/')
            ->assertStatus(200)
            ->assertSeeInOrder(['<title>', $settings['site_title'], '</title>'])
            ->assertSee($settings['description'])
            ->assertSeeInOrder(['<style>', $settings['primary_color'], '</style>'])
            ->assertSeeInOrder(['<style>', $settings['secondary_color'], '</style>'])
            ->assertSeeInOrder(['<style>', $settings['tertiary_color'], '</style>'])
            ->assertSeeInOrder(['<footer', $settings['footer'], '</footer>']);

        $this->get(route('app.faq'))
            ->assertStatus(200)
            ->assertViewIs('faq')
            ->assertSee(e($settings['faq']));

        Cache::flush();
    }

    /**
     * Test changing site logo
     *
     * @return void
     */
    public function testEditSiteLogo()
    {
        $this->seed();
        $this->loginAsAdmin();
        Cache::flush();

        $response = $this->followingRedirects()->post(route('app.settings.update-image', ['image' => 'logo']), [
            'logo' => $this->mockUploadedImage(),
        ]);

        $response
            ->assertStatus(200)
            ->assertViewIs('settings')
            ->assertSee('Settings image has been updated.');

        $this->assertDatabaseHas('settings', ['name' => 'logo']);

        $setting = Setting::where('name', '=', 'logo')->first();
        $this->assertNotNull($setting);

        $this->assertFileExists($setting->getFirstMedia('logo')->getPath());

        $this->get('/')
            ->assertStatus(200)
            ->assertSee("<img class=\"profiles-logo\" src=\"{$setting->value}\"");
    }

    /**
     * Test changing site favicon
     *
     * @return void
     */
    public function testEditSiteFavicon()
    {
        $this->seed();
        $this->loginAsAdmin();
        Cache::flush();

        $response = $this->followingRedirects()->post(route('app.settings.update-image', ['image' => 'favicon']), [
            'favicon' => $this->mockUploadedImage(),
        ]);

        $response
            ->assertStatus(200)
            ->assertViewIs('settings')
            ->assertSee('Settings image has been updated.');

        $this->assertDatabaseHas('settings', ['name' => 'favicon']);

        $setting = Setting::where('name', '=', 'favicon')->first();
        $this->assertNotNull($setting);

        $this->assertFileExists($setting->getFirstMedia('favicon')->getPath());

        $this->get('/')
            ->assertStatus(200)
            ->assertSee("<link rel=\"icon\" href=\"{$setting->value}\"");
    }

}
