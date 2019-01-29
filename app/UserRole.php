<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

// Custom pivot model for User-Role relation
class UserRole extends Pivot
{
    /** @var array attribute casting for additional pivot columns */
    protected $casts = [
        'options' => 'array',
    ];

}
