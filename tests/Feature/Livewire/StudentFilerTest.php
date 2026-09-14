<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\StudentFiler;
use App\Profile;
use App\Student;
use App\StudentData;
use Tests\Feature\Traits\HasJson;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Feature\Traits\LoginWithRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentFilerTest extends TestCase
{
    use HasJson;
    use LoginWithRole;
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function testFileStudentApplication(): void
    {
        $profile = Profile::factory()
                    ->hasData()
                    ->create();

        //Create student app as follow up
        $student = Student::factory()
                    ->submitted()
                    ->has(StudentData::factory(), 'research_profile')
                    ->hasAttached($profile, ['status' => 'follow up'], 'faculty')
                    ->create();

        $this->loginAsUser($profile->user);

        Livewire::test(StudentFiler::class, [
            'profile' => $profile,
            'student' => $student,
            'status' => 'follow up',
        ])
            ->call('updateStatus', 'accepted', 'Accepted') // File as accepted
            ->assertDispatched('profileStudentStatusUpdated');

        $this->assertDatabaseHas('profile_student', [
            'profile_id' => $profile->id,
            'student_id' => $student->id,
            'status' => 'accepted',
        ]);
    }
}
