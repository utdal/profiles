<?php

namespace Tests\Feature;

use App\School;
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
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Test editing site settings.
     *
     * @return void
     */
    public function testEditSiteSettings()
    {
        $this->loginAsAdmin();
        Cache::flush();

        $schools = School::factory()->count(3)->create();

        $this->get(route('app.settings.edit'))
            ->assertStatus(200)
            ->assertViewIs('settings')
            ->assertSee($schools->pluck('display_name')->all());

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
            'student_info' => '<div>' . $this->faker->paragraph() . '</div>',
            'student_info_overlay' => $this->faker->boolean(),
            'student_participating_schools' => [
                $this->faker->randomElement($schools->pluck('short_name')->all()),
            ],
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
            ->assertSee($settings['site_title'])
            ->assertSee($settings['description'])
            ->assertSee($settings['account_name'])
            ->assertSee($settings['forgot_password_url'])
            ->assertSee("id=\"setting[school_search_shortcut]\" value=\"1\" " . ($settings['school_search_shortcut'] ? ' checked ' : ''), false)
            ->assertSee("id=\"setting[profile_search_shortcut]\" value=\"1\" " . ($settings['profile_search_shortcut'] ? ' checked ' : ''), false)
            ->assertSee($settings['faq'])
            ->assertSee($settings['footer'])
            ->assertSee($settings['student_info'])
            ->assertSee("id=\"setting[student_info_overlay]\" value=\"1\" " . ($settings['student_info_overlay'] ? ' checked ' : ''), false)
            ->assertSee("option value=\"{$settings['student_participating_schools'][0]}\" selected", false);

        foreach ($settings as $setting_name => $setting_value) {
            $this->assertDatabaseHas('settings', [
                'name' => $setting_name,
                'value' => is_array($setting_value) ? json_encode($setting_value) : $setting_value,
            ]);
        }

        Auth::logout();

        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee($settings['forgot_password_url'])
            ->assertSee($settings['account_name']);

        $this->get('/')
            ->assertStatus(200)
            ->assertSeeInOrder(['<title>', e($settings['site_title']), '</title>'], false)
            ->assertSee($settings['description'])
            ->assertSeeInOrder(['<style>', $settings['primary_color'], '</style>'], false)
            ->assertSeeInOrder(['<style>', $settings['secondary_color'], '</style>'], false)
            ->assertSeeInOrder(['<style>', $settings['tertiary_color'], '</style>'], false)
            ->assertSeeInOrder(['<footer', e($settings['footer']), '</footer>'], false);

        $this->get(route('app.faq'))
            ->assertStatus(200)
            ->assertViewIs('faq')
            ->assertSee($settings['faq']);

        $this->get(route('students.about'))
            ->assertStatus(200)
            ->assertSee($settings['student_info'], false);

        Cache::flush();
    }

    /**
     * Test changing site logo
     *
     * @return void
     */
    public function testEditSiteLogo()
    {
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
            ->assertSee("<img class=\"profiles-logo\" src=\"{$setting->value}\"", false);
    }

    /**
     * Test changing site favicon
     *
     * @return void
     */
    public function testEditSiteFavicon()
    {
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
            ->assertSee("<link rel=\"icon\" href=\"{$setting->value}\"", false);
    }

    /**
     * Test changing student info image
     *
     * @return void
     */
    public function testEditStudentInfoImage()
    {
        $this->loginAsAdmin();
        Cache::flush();

        $response = $this->followingRedirects()->post(route('app.settings.update-image', ['image' => 'student_info_image']), [
            'student_info_image' => $this->mockUploadedImage(),
        ]);

        $response
            ->assertStatus(200)
            ->assertViewIs('settings')
            ->assertSee('Settings image has been updated.');

        $this->assertDatabaseHas('settings', ['name' => 'student_info_image']);

        $setting = Setting::where('name', '=', 'student_info_image')->first();
        $this->assertNotNull($setting);

        $this->assertFileExists($setting->getFirstMedia('student_info_image')->getPath());

        $this->get(route('students.about'))
            ->assertStatus(200)
            ->assertSee("<img class=\"card-img \" src=\"{$setting->value}\"", false);
    }

}
