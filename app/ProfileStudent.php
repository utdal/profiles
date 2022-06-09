<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;

class ProfileStudent extends Pivot implements Auditable
{
    use HasAudits;

    /** @var array Possible profile-student statuses */
    public static $statuses = [
        '' => 'New',
        'follow up' => 'Follow Up',
        'accepted' => 'Accepted to Lab',
        'maybe later' => 'Maybe Later',
        'not interested' => 'Not Interested',
    ];

    /** @var array Possible profile-student status icons */
    public static $icons = [
        '' => 'fas fa-inbox',
        'follow up' => 'fas fa-info',
        'accepted' => 'fas fa-check',
        'maybe later' => 'fas fa-pause',
        'not interested' => 'fas fa-times',
    ];

    /**
     * Modify the audit data
     *
     * @param array $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        // Set the id to the student_id, since we don't have a pivot table id
        $data['auditable_id'] = $data['auditable_id'] ?? $this->student_id ?? 0;

        // When updating pivot data, always include profile & student ids
        if ($data['event'] === 'updated') {
            if (!Arr::has($data, 'old_values.profile_id')) {
                $data['old_values']['profile_id'] = $this->profile_id;
            }
    
            if (!Arr::has($data, 'old_values.student_id')) {
                $data['old_values']['student_id'] = $this->student_id;
            }

            if (!Arr::has($data, 'new_values.profile_id')) {
                $data['new_values']['profile_id'] = $this->profile_id;
            }
    
            if (!Arr::has($data, 'new_values.student_id')) {
                $data['new_values']['student_id'] = $this->student_id;
            }
        }

        return $data;
    }
}
