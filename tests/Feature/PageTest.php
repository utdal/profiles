<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group page
 */
class PageTest extends TestCase
{
    /**
     * Test that the Homepage is OK.
     *
     * @return void
     */
    public function testHomepageIsOk(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the Homepage is OK.
     *
     * @return void
     */
    public function testBrowsePageIsOk(): void
    {
        $response = $this->get('/browse');

        $response->assertStatus(200);
    }

    /**
     * Test if we can see the student about page.
     * 
     * @return void
     */
    public function testStudentAboutPageIsOk(): void
    {
        $response = $this->get(route('students.about'));

        $response->assertStatus(200);
    }

    /**
     * Test that the Login page is OK.
     *
     * @return void
     */
    public function testLoginPageIsOk(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
