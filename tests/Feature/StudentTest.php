<?php

namespace Tests\Feature;

use App\Student;
use App\StudentData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\HasJson;
use Tests\Feature\Traits\LoginWithRole;
use Tests\TestCase;

/**
 * @group student
 */
class StudentTest extends TestCase
{
    use HasJson;
    use LoginWithRole;
    use RefreshDatabase;

    /** @var bool Run the default seeder before each test. */
    protected $seed = true;

    /**
     * Test Student creation.
     */
    public function testStudentCreation(): void
    {
        $student = Student::factory()->submitted()->create();
        $student_data = StudentData::factory()->for($student)->create();

        $this->assertDatabaseHas('students', $student->getAttributes());
        $this->assertDatabaseHas('student_data', array_merge($student_data->getAttributes(), [
            'student_id' => $student->id,
            'data' => $this->castToJson($student_data->data),
        ]));
        $this->assertDatabaseHas('users', $student->user->getAttributes());
    }

    /**
     * Test Student visibility
     *
     * @param string $user_role
     * @dataProvider userRolesProvider
     * @return void
     */
    public function testStudentShowPolicy($user_role): void
    {
        $student = Student::factory()->submitted()->has(StudentData::factory(), 'research_profile')->create();

        if ($user_role === 'data_owner') {
            $this->loginAsUser($student->user);
        } else {
            $this->loginAsUserWithRole($user_role);
        }

        $show_route = route('students.show', ['student' => $student]);

        if (in_array($user_role, ['faculty', 'data_owner', 'students_admin', 'site_admin'])) {
            $this->get($show_route)
                ->assertStatus(200)
                ->assertViewIs('students.show')
                ->assertSee($student->full_name);
        } elseif ($user_role === 'guest') {
            $this->get($show_route)
                ->assertRedirect(route('login'));
        } else {
            $this->get($show_route)
                ->assertForbidden();
        }

        $index_route = route('students.index');

        if (in_array($user_role, ['faculty', 'students_admin', 'site_admin'])) {
            $this->get($index_route)
                ->assertStatus(200)
                ->assertViewIs('students.index')
                ->assertSee($student->full_name);
        } elseif ($user_role === 'guest') {
            $this->get($index_route)
                ->assertRedirect(route('login'));
        } else {
            $this->get($index_route)
                ->assertForbidden();
        }
    }

    /**
     * Test Student unsubmit application
     *
     * @return void
     */
    public function testStudentUnsubmit(): void
    {
        $student = Student::factory()->submitted()->has(StudentData::factory(), 'research_profile')->create();

        $this->loginAsUser($student->user);

        $index_route = route('students.index');
        $show_route = route('students.show', ['student' => $student]);
        $unsubmit_route = route('students.status', ['student' => $student, 'status' => 'drafted']);

        $this->get($show_route)
            ->assertStatus(200)
            ->assertViewIs('students.show')
            ->assertSee('Un-submit');

        $this->followingRedirects()->get($unsubmit_route)
            ->assertStatus(200)
            ->assertSee('Student profile status updated');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'status' => 'drafted',
        ]);

        $this->loginAsUserWithRole('site_admin');

        // drafted applications are hidden by default in the index
        $this->get($index_route)
            ->assertStatus(200)
            ->assertViewIs('students.index')
            ->assertDontSee($student->full_name);
    }
}
