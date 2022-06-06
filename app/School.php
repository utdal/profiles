<?php

namespace App;

use App\User;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model implements Auditable
{
    use HasAudits;
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'short_name',
        'aliases'
    ];

    /**
     * @var string Delimiter of aliases
     */
    const ALIAS_DELIMITER = ';';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'short_name';
    }

    public function addAlias($new_alias)
    {
        if (!isset($new_alias) || !is_string($new_alias)) {
            return false;
        }
        elseif (!$this->aliases) {
            $this->aliases = $new_alias;
        }
        elseif (strpos($this->aliases, $new_alias) !== false) {
            $this->aliases .= self::ALIAS_DELIMITER . $new_alias;
        }
        $this->save();

        return $this;
    }

    public function hasName($name, $include_aliases = true, $case_insensitive = false)
    {
        $school_names = [$this->name, $this->display_name, $this->short_name];

        if ($include_aliases) {
            $school_names = array_merge($school_names, explode(self::ALIAS_DELIMITER, $this->aliases));
        }

        if ($case_insensitive) {
            $name = strtolower($name);
            $school_names = array_map('strtolower', $school_names);
        }

        return in_array($name,$school_names);
    }

    public function scopeWithName($query,$name) {
        return $query->where('name',$name)
                    ->orWhere('short_name',$name)
                    ->orWhere('display_name',$name)
                    ->orWhere('aliases','like','%'.$name.'%')
                    ->orderByRaw("name = ? DESC, short_name = ? DESC, display_name = ? DESC", [$name, $name, $name]);
    }

    public function scopeWithNames($query, $names) {
        return $query->whereIn('name', $names)
                    ->orWhereIn('short_name', $names)
                    ->orWhereIn('display_name', $names)
                    ->orWhereRaw("aliases REGEXP ?", [implode('|', $names)])
                    ->orderBy('name', 'DESC');
    }

    public function scopeWithNameLike($query, $name)
    {
        return $query->where('name', 'like', "%$name%")
            ->orWhere('short_name', 'like', "%$name%")
            ->orWhere('display_name', 'like', "%$name%")
            ->orWhere('aliases', 'like', "%$name%")
            ->orderByRaw("name = ? DESC, short_name = ? DESC, display_name = ? DESC", [$name, $name, $name]);
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * School has many Users
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * School has many Profiles (through Users)
     *
     * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function profiles()
    {
        return $this->hasManyThrough(Profile::class, User::class);
    }
}
