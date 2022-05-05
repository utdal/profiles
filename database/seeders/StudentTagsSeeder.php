<?php

namespace Database\Seeders;

use App\Student;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Seeder;

class StudentTagsSeeder extends Seeder
{

    const NEW_TAGS_ASSOCIATION = [
        "Biopsychology" => [
            "neurobiology"
        ],
        "Clinical Psychology / Psychopathology" => [
            "addiction",
            "anxiety",
            "autism",
            "biomarkers",
            "brain",
            "classical conditioning",
            "depression",
            "disability",
            "drug addiction",
            "psychiatric disorder ",
            "substance use",
             "behavior",
            "personality pathology",
            "therapy"
        ],
        "Cognition / Cognitive Psychology" => [
            "cognition",
            "decision making",
            "face perception",
            "human cognition",
            "memory",
            "motivation",
            "music",
            "music perception",
            "reasoning",
            "retrieval",
            "reward",
            "social cognition"
        ],
        "Cognitive Development" => [
            "cognitive development",
            "learning",
            "school readiness ",
            "skepticism",
            "skill",
            "skill learning"
        ],
        "Cognitive Neuroscience" => [
            "brain training",
            "cognitive neuroscience",
            "EEG",
            "electrophysiology",
            "human neuroimaging"
        ],
        "Developmental Psychology" => [
            "adults",
            "aging",
            "Alzheimer’s disease ",
            "child development",
            "children",
            "cognitive aging",
            "early intervention",
            "healthy aging",
            "infant",
            "lifespan",
            "motor development",
            "normal aging",
            "parent-child interaction",
            "parent-child relations",
            "parenting",
             "poverty",
             "sex differences",
             "social development",
             "typical"
        ],
        "Health Psychology" => [
            "conflict",
            "coping",
            "drug discovery",
            "habit",
            "health",
            "immunology",
            "metabolism",
            "nutrition",
            "reconcile",
            "stress"
        ],
        "Hearing / Audiology" => [
            "audition",
            "auditory"
        ],
        "Medical Devices & Assistive Technology" => [
            "educational technology"
        ],
        "Neuroscience / Neuropsychology" => [
            "affective neuroscience",
            "amyloid",
            "brain networks ",
            "Cultural Neuroscience",
            "fMRI",
            "hippocampus",
            "human DRG",
            "molecular",
            "mouse",
            "MRI",
            "neuroimaging",
            "neuropsychology",
            "neuroscience",
            "nociceptor",
            "pharmacology",
            "plasticity",
            "prefrontal cortex",
            "social neuroscience",
            "synaptic plasticity",
            "TMS",
            "vagus nerve stimulation (VNS)"
        ],
        "Pain" => [
            "migraine",
            "pain"
        ],
        "Personality Psychology" => [
            "narcissism",
            "personality",
            "prosociality",
            "behavior",
            "personality pathology"
        ],
        "Social Psychology" => [
            "body image",
            "body perception",
            "couples",
            "cross-cultural",
            "cultural diversity",
            "culture",
            "emotion",
            "emotions",
            "family",
            "interaction",
            "intimacy",
            "morality",
            "relationships",
            "signaling",
            "social communication",
            "social trait perception",
            "socioeconomic status"
        ],
        "Speech / Language" => [
            "bilingual",
            "communication",
            "developmental language disorder",
            "language",
            "language development",
            "literacy",
            "motor",
            "semantic",
            "speech",
            "speech development",
            "speech perception",
            "speech therapy",
            "word learning"
        ],
        "Speech and Language Disorders" => [
            "assessment",
            "disordered",
            "SLI"
        ],
        "Statistics / Measurement / Modeling / Machine Learning" => [
            "artificial intelligence",
            "computational",
            "computational modeling",
            "dyadic data analysis",
            "eyetracking",
            "face recognition",
            "machine learning",
            "mathematical psychology",
            "psychometrics"
        ],
    ];

    /**
     * Run the StudentTags Seeder
     *
     * @return void
     */
    public function run()
    {
        $existing_type = 'App\Student';
        $placeholder_type = 'App\StudentNew';

        $this->createNewTags($placeholder_type);
        $this->syncStudentApplicationsNewTags($existing_type, $placeholder_type);
        $this->deleteTags($existing_type);
        $this->renameTagType($placeholder_type, $existing_type);

        $this->createNewTags('App\Profile');
    }

    public function createNewTags(string $type): void
    {
        $new_tags_created = Tag::findOrCreate(array_keys($this::NEW_TAGS_ASSOCIATION), $type)->count();
        $this->lineAndLog("CREATED: $new_tags_created new $type Tags have been found or created.");
    }

    public function deleteTags(string $type): void
    {
        $old_tags_deleted = Tag::where('type', $type)->delete();
        $this->lineAndLog("DELETED: $old_tags_deleted $type Tags have been deleted.");
    }

    public function renameTagType(string $old_type, string $new_type): void
    {
        $tags_type_renamed = Tag::where('type', $old_type)->update(['type' => $new_type]);
        $this->lineAndLog("RENAMED: $tags_type_renamed $old_type Tags have been renamed to $new_type.");
    }

    /**
     * Loop through the students with tags,
     * find the new tags associated to store them in an array
     * and sync the new tags for each student.
     *
     * @return void
     */
    public function syncStudentApplicationsNewTags(string $old_type, string $new_type): void
    {
        $new_tags_association = collect($this::NEW_TAGS_ASSOCIATION);
        $new_tags = Tag::WithType($new_type)->get();
        $students_with_tags = Student::with('tags')->has('tags')->get();

        foreach ($students_with_tags as $student) {

            $student_new_tags = [];
            $tags_not_found = [];
            
            foreach ($student->tags as $student_old_tag) {

                $elem = $new_tags_association->filter(function ($value, $key) use ($student_old_tag) {
                    return in_array($student_old_tag->name, $value);
                });

                $new_tag = $new_tags->filter(function($item) use ($elem) {
                    return trim(strtolower($item->name)) == trim(strtolower($elem->keys()->first()));
                })->first();

                if (isset($new_tag) && !in_array($new_tag, $student_new_tags)) {
                    array_push($student_new_tags, $new_tag);
                } else {
                    array_push($tags_not_found, $student_old_tag);
                }
            }
            if (!empty($student_new_tags)) {

                $this->lineAndLog(sizeof($student->tags)." Tags have been found to assign to student ID: ". $student->id);

                $student->syncTagsWithType($student_new_tags, $old_type);

                foreach ($student_new_tags as $student_new_tag) {
                    $this->lineAndLog(" The new tag ". $student_new_tag->name ." has been assigned to student ID: ". $student->id);
                }
                
                $this->lineAndLog(sizeof($student_new_tags)." New tags have been assigned to the student ID: " . $student->id);   

            } elseif (!empty($tags_not_found)) {

                $this->lineAndLog("The following" . sizeof($tags_not_found) ." could not be found to assign to student ID: ". $student->id, 'error');

                foreach ($tags_not_found as $tag_not_found) {
                    $this->lineAndLog("The tag " . $tag_not_found->name . " could not be found to assign to student ID: ". $student->id, 'error');
                }

            }
        }
    }

    /**
     * Output a message to the console and log file
     */
    public function lineAndLog(string $message, string $type = 'info'): void
    {
        $this->command->$type($message);
        Log::$type($message);
    }

}

