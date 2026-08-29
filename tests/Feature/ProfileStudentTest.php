<?php

namespace Tests\Feature;

use App\Profile;
use App\Student;
use App\StudentData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * Tests the relationship between faculty profiles and students.
 * 
 * @group profilestudent
 */
class ProfileStudentTest extends TestCase
{
    use LoginWithRole;
    use RefreshDatabase;
    use WithFaker;

    /** @var bool Run the default seeder before each test. */
    protected $seed = true;

    /**
     * Test Profile (faculty) specific view of students
     *
     * @return void
     */
    public function testProfileStudentView(): void
    {
        $profile = Profile::factory()
            ->hasData()
            ->create();

        $student = Student::factory()
            ->submitted()
            ->has(StudentData::factory(), 'research_profile')
            ->hasAttached($profile, [], 'faculty')
            ->create();

        $this->assertDatabaseHas('profile_student', [
            'profile_id' => $profile->id,
            'student_id' => $student->id,
            'status' => null,
        ]);

        $show_route = route('students.show', ['student' => $student]);
        $profile_student_route = route('profiles.students', ['profile' => $profile]);

        $this->loginAsUser($student->user);

        $this->get($show_route)
            ->assertStatus(200)
            ->assertViewIs('students.show')
            ->assertSee($profile->full_name);

        $this->loginAsUserWithRole('faculty', $profile->user);

        $this->get($profile_student_route)
            ->assertStatus(200)
            ->assertViewIs('students.profile-students')
            ->assertSee($student->full_name);
    }
    
    /**
     * Test Profile (non-faculty) specific view of students
     *
     * @return void
     */
    public function testNonFcultyProfileStudentView(): void
    {
        $associated_profile = Profile::factory()
            ->hasData()
            ->create();

        $non_associated_profile= Profile::factory()
            ->hasData()
            ->create();

        $student = Student::factory()
            ->submitted()
            ->has(StudentData::factory(), 'research_profile')
            ->hasAttached($associated_profile, [], 'faculty')
            ->create();

        $profile_student_route = route('profiles.students', ['profile' => $non_associated_profile]);
        $show_route = route('students.show', ['student' => $student]);

        $this->loginAsUserWithRole('staff', $non_associated_profile->user);
        $this->get($show_route)
            ->assertStatus(403);

        $this->loginAsUserWithRole('faculty', $non_associated_profile->user);
        $this->get($profile_student_route)
            ->assertStatus(200)
            ->assertViewIs('students.profile-students')
            ->assertDontSee($student->full_name);

        $this->get($show_route)
            ->assertStatus(200);
        
        $profile_student_route = route('profiles.students', ['profile' => $associated_profile]);
        
        $this->loginAsUserWithRole('staff', $associated_profile->user);
        $this->get($profile_student_route)
            ->assertStatus(200)
            ->assertViewIs('students.profile-students')
            ->assertSee($student->full_name);
        
            $this->get($show_route)
            ->assertStatus(200)
            ->assertSee($student->full_name);
    }
}
