<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ProfileStudents;
use App\Profile;
use App\Student;
use App\StudentData;
use Tests\Feature\Traits\HasJson;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Feature\Traits\LoginWithRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileStudentsTest extends TestCase
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
    public function testStudentAppFilingStatusAndCountAfterUpdatingFilingStatus(): void
    {
        $profile = Profile::factory()
                    ->hasData()
                    ->create();

        $student = Student::factory()
                    ->submitted()
                    ->has(StudentData::factory(), 'research_profile')
                    ->hasAttached($profile, ['status' => 'follow up'], 'faculty')
                    ->create();

        $this->loginAsUser($profile->user);

        $component = Livewire::test(ProfileStudents::class, [
            'profile' => $profile,
        ]);

        $component->assertSeeHtmlInOrder([
            'id="tab_pill_follow-up"',
            '(1)',
            'id="tab_pill_accepted"',
            '(0)',
            'id="tab_pill_maybe-later"',
        ]);

        $profile->students()->updateExistingPivot($student->id, [
            'status' => 'accepted',
        ]);

        $component->dispatch('profileStudentStatusUpdated');

        $component->assertSeeHtmlInOrder([
            'id="tab_pill_follow-up"',
            '(0)',
            'id="tab_pill_accepted"',
            '(1)',
            'id="tab_pill_maybe-later"',
        ]);
    }

    /** @test */
    public function testFilingStatusApplicationCount(): void
    {
        $profile = Profile::factory()
                    ->hasData()
                    ->create();

        $student = Student::factory()
                    ->submitted()
                    ->has(StudentData::factory(), 'research_profile')
                    ->hasAttached($profile, ['status' => 'follow up'], 'faculty')
                    ->create();

        $this->loginAsUser($profile->user);

        $component = Livewire::test(ProfileStudents::class, [
            'profile' => $profile,
        ]);

        $component->assertSeeHtmlInOrder([
            'id="tab_pill_"', '(0)',
            'id="tab_pill_follow-up"', '(1)',
            'id="tab_pill_accepted"', '(0)',
            'id="tab_pill_maybe-later"', '(0)',
            'id="tab_pill_not-interested"', '(0)',
        ]);

        $this->assertEquals(1, substr_count($component->html(), $student->full_name));

        $profile->students()->updateExistingPivot($student->id, [
            'status' => 'accepted',
        ]);

        $component->dispatch('profileStudentStatusUpdated');

        $component->assertSeeHtmlInOrder([
            'id="tab_pill_"', '(0)',
            'id="tab_pill_follow-up"', '(0)',
            'id="tab_pill_accepted"', '(1)',
            'id="tab_pill_maybe-later"', '(0)',
            'id="tab_pill_not-interested"', '(0)',
        ]);

        $this->assertEquals(1, substr_count($component->html(), $student->full_name));
    }
}
