<?php

namespace App;

use App\School;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;

class UserSetting extends Model implements Auditable
{
    use HasFactory, HasAudits;

    /** @var array Whitelist of mass-editable columns */
    protected $fillable = [
        'additional_departments',
        'additional_schools',
    ];

    /** @var array Attributes that should be mutated to dates. */
    protected $dates = ['created_at', 'updated_at'];

    /** @var array Attribute casting */
    protected $casts = [
        'additional_departments' => 'array',
        'additional_schools' => 'array',
    ];

    ///////////////
    // Accessors //
    ///////////////

    /**
     * Accessor to get additional schools as a collection of Schools
     *
     * @param mixed $additional_schools
     * @return null|\Illuminate\Database\Eloquent\Collection
     */
    public function getAdditionalSchoolsAttribute($additional_schools)
    {
        $additional_schools = $this->castAttribute('additional_schools', $additional_schools);

        if (!$additional_schools) {
            return $additional_schools;
        }

        return School::whereIn('id', array_keys($additional_schools))->get();
    }

    /**
     * Virtual attribute to get the names of additional schools
     *
     * @return null|array
     */
    public function getAdditionalSchoolNamesAttribute()
    {
        return $this->castAttribute('additional_schools', $this->getOriginal('additional_schools'));
    }

    ////////////////
    // Relations  //
    ////////////////

    /**
     * UserSetting belongs to one User (one-to-one)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
