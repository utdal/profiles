<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Helpers\Country;

class ProfileData extends Model implements HasMedia, Auditable
{
    use HasFactory;
    use HasAudits;
    use InteractsWithMedia;

    /** @var string The database table used by the model */
    protected $table = 'profile_data';

    /** @var array attribute casting map */
    protected $casts = [
        'public' => 'boolean',
        'data' => 'array'
    ];

    /** @var array mass-assignment fillable attributes */
    protected $fillable = [
        'profile_id',
        'type',
        'data',
        'sort_order',
        'public'
    ];

    /////////////////////
    // General Methods //
    /////////////////////

    /**
     * Get the attributes that can be exposed via the API.
     *
     * @return array
     */
    public static function apiAttributes()
    {
        return ['profile_id', 'type', 'data', 'sort_order'];
    }

    /**
     * Registers media conversions.
     *
     * @param  Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->registerImageThumbnails($media, 'thumb', 150);
        $this->registerImageThumbnails($media, 'medium', 350);
    }

    /**
     * Registers image thumbnails.
     *
     * @param  Media|null $media
     * @param  string     $name       Name of the thumbnail
     * @param  int        $size       Max dimension in pixels
     * @param  string     $collection Name of the collection for the thumbnails
     * @return void
     */
    protected function registerImageThumbnails(Media $media = null, $name, $size, $collection = 'images'): void
    {
        $this->addMediaConversion($name)
            ->width($size)
            ->height($size)
            ->performOnCollections($collection);
    }

    /**
     * Update data with new values for specific nested keys using dot notation
     *
     * @param array $new_data Array of values to insert/update
     * @return bool
     */
    public function updateData(array $new_data): bool
    {
        $data = $this->data;

        foreach ($new_data as $key => $new_value) {
            data_set($data, $key, $new_value);
        }

        return $this->update(['data' => $data]);
    }

    /**
     * Adds to data, inserting new items and converting existing items to arrays as needed.
     *
     * @param array $new_data Array of values to insert
     * @return bool
     */
    public function insertData(array $new_data): bool
    {
        return $this->update(['data' => collect($this->data)->mergeRecursive($new_data)->all()]);
    }

    /**
     * Removes a given data item for the specified nested key using dot notation
     *
     * @param string $key
     * @return bool
     */
    public function removeData(string $key): bool
    {
        $data = $this->data;

        Arr::forget($data, $key);

        return $this->update(['data' => $data]);
    }

    /**
     * Increments a datum for a specific nested key using dot notation
     *
     * @param string $key
     * @param int $increment
     * @return bool
     */
    public function incrementDatum(string $key, int $increment = 1): bool
    {
        $data = $this->data;

        data_set($data, $key, data_get($data, $key, 0) + $increment);

        return $this->update(['data' => $data]);
    }

    /**
     * Decrements a datum for a specified nested key using dot notation
     *
     * @param string $key
     * @param int $decrement
     * @param bool $allow_negative
     * @return bool
     */
    public function decrementDatum(string $key, int $decrement = 1, bool $allow_negative = true): bool
    {
        $data = $this->data;
        $new_value = data_get($data, $key, 0) - $decrement;

        data_set($data, $key, $allow_negative ? $new_value : max($new_value, 0));

        return $this->update(['data' => $data]);
    }

    /**
     * Gets a list of unique values of the given key from stored records of the given type
     *
     * @param string $type
     * @param string $key
     * @return \Illuminate\Support\Collection<int, string>
     */
    public static function uniqueValuesFor(string $type, string $key)
    {
        return self::whereType($type)
            ->pluck('data')
            ->pluck($key)
            ->flatten()
            ->unique()
            ->filter();
    }

    ///////////////////////////////////
    // Mutators & Virtual Attributes //
    ///////////////////////////////////

    public function getImageAttribute()
    {
        return $this->getFirstMedia('images');
    }

    /**
     * Build a citation string from the patent's members. The title is not
     * included — the view renders it separately with its own format.
     *
     * Format: "jurisdiction - Number (mm/dd/yyyy), jurisdiction - pending - Number (mm/dd/yyyy), ..."
     *
     * - Granted members: "jurisdiction - Number (published_date)"
     * - Pending members: "jurisdiction - pending - Number (filed_date)"
     * - A member's own patent_title prefixes its entry only when it differs from
     *   the group title after normalization (case/punctuation variants are omitted)
     * - European jurisdictions sharing a patent number collapse into one "EPO - Number" entry
     *   using the earliest published_date among them
     */
    public function getPatentCitationAttribute(): ?string
    {
        if ($this->type !== 'patents') {
            return null;
        }

        $data = $this->data;
        $co_inventors = trim((string) ($data['co_inventors'] ?? ''));
        $group_title_normalized = $this->normalizeCitationTitle((string) ($data['title'] ?? ''));
        $members = $data['members'] ?? [];

        if (!is_array($members) || empty($members)) {
            return $co_inventors !== '' ? "co-inventors: {$co_inventors}" : null;
        }

        $entries = [];
        $ep_buckets = [];

        $european_codes = ['EP', 'BE', 'CH', 'DE', 'ES', 'FR', 'GB', 'IE', 'IT', 'NL'];

        foreach ($members as $member) {
            $jurisdiction = trim((string) ($member['jurisdiction'] ?? ''));
            $status = $member['status'] ?? 'granted';
            $patent_no = trim((string) ($member['patent_no'] ?? ''));
            $internal_id = trim((string) ($member['patent_internal_id'] ?? ''));

            $identifier = $patent_no !== '' ? $patent_no : $internal_id;
            if ($jurisdiction === '' || $identifier === '') {
                continue;
            }

            if ($status === 'granted'
                && $patent_no !== ''
                && in_array($jurisdiction, $european_codes, true)) {

                $date_str = trim((string) ($member['published_date'] ?? ''));
                $date_sortable = $this->dateSortable($date_str);

                if (!isset($ep_buckets[$patent_no])) {
                    $ep_buckets[$patent_no] = [
                        'patent_no' => $patent_no,
                        'title_display' => $this->variantTitle($member, $group_title_normalized),
                        'date_display' => $this->formatCitationDate($date_str),
                        'date_sortable' => $date_sortable,
                    ];
                } elseif ($date_sortable < $ep_buckets[$patent_no]['date_sortable']) {
                    $ep_buckets[$patent_no]['title_display'] = $this->variantTitle($member, $group_title_normalized);
                    $ep_buckets[$patent_no]['date_display'] = $this->formatCitationDate($date_str);
                    $ep_buckets[$patent_no]['date_sortable'] = $date_sortable;
                }
                continue;
            }

            $entries[] = $this->formatCitationMember($member, $group_title_normalized);
        }

        foreach ($ep_buckets as $bucket) {
            $entry = "EPO - {$bucket['patent_no']}";
            if ($bucket['title_display'] !== '') {
                $entry = "{$bucket['title_display']} - {$entry}";
            }
            if ($bucket['date_display'] !== '') {
                $entry .= " ({$bucket['date_display']})";
            }
            $entries[] = $entry;
        }

        $entries = array_filter($entries, fn ($e) => $e !== null && $e !== '');

        if (empty($entries)) {
            return $co_inventors !== '' ? "co-inventors: {$co_inventors}" : null;
        }

        $citation = implode(', ', $entries);

        if ($co_inventors !== '') {
            $citation .= " - co-inventors: {$co_inventors}";
        }

        return $citation;
    }

    /**
     * Format a single member as a citation entry.
     */
    private function formatCitationMember(array $member, string $group_title_normalized): ?string
    {
        $jurisdiction = trim((string) ($member['jurisdiction'] ?? ''));
        $status = $member['status'] ?? 'published';
        $patent_no = trim((string) ($member['patent_no'] ?? ''));
        $internal_id = trim((string) ($member['patent_internal_id'] ?? ''));

        $identifier = $patent_no !== '' ? $patent_no : $internal_id;
        if ($jurisdiction === '' || $identifier === '') {
            return null;
        }

        $jurisdiction_display = match ($jurisdiction) {
            'EP' => 'EPO',
            'US' => 'US',
            'KR' => 'Korea',
            default => Country::name($jurisdiction) ?? $jurisdiction,
        };

        $date_field = $status === 'pending' ? 'filed_date' : 'published_date';
        $date_str = trim((string) ($member[$date_field] ?? ''));
        $date_display = $this->formatCitationDate($date_str);

        $entry = $jurisdiction_display;

        $variant_title = $this->variantTitle($member, $group_title_normalized);
        if ($variant_title !== '') {
            $entry = "{$variant_title} - {$entry}";
        }

        if ($status === 'pending') {
            $entry .= ' - pending';
        }
        $entry .= " - {$identifier}";
        if ($date_display !== '') {
            $entry .= " ({$date_display})";
        }

        return $entry;
    }

    /**
     * Return the member's own title when it's different from the group
     * title, or '' when it matches after normalization (or is empty).
     */
    private function variantTitle(array $member, string $group_title_normalized): string
    {
        $member_title = trim((string) ($member['patent_title'] ?? ''));
        if ($member_title === '') {
            return '';
        }

        return $this->normalizeCitationTitle($member_title) === $group_title_normalized
            ? ''
            : $member_title;
    }

    /**
     * Normalize a title for comparison: lowercase, strip punctuation,
     * collapse whitespace. Mirrors PatentFamilyGrouper::normalizeTitle().
     */
    private function normalizeCitationTitle(string $title): string
    {
        $t = mb_strtolower(trim($title));
        $t = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        return trim($t);
    }

    /**
     * Format a date string as mm/dd/yyyy. Returns empty string if unparseable.
     */
    private function formatCitationDate(string $date_str): string
    {
        if ($date_str === '') {
            return '';
        }
        try {
            return \Carbon\Carbon::parse($date_str)->format('m/d/Y');
        } catch (\Throwable) {
            return '';
        }
    }

    /**
     * Return a sortable integer (YYYYMMDD) from a date string. Returns PHP_INT_MAX if unparseable.
     */
    private function dateSortable(string $date_str): int
    {
        if ($date_str === '') {
            return PHP_INT_MAX;
        }
        try {
            return (int) \Carbon\Carbon::parse($date_str)->format('Ymd');
        } catch (\Throwable) {
            return PHP_INT_MAX;
        }
    }

    /**
     * Get the image URL. ($this->image_url)
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return url($this->getFirstMediaUrl('images', 'medium') ?: '/img/default.png');
    }

    /**
     * Get profile data as if it was a natural attribute.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute('data')[$key] ?? parent::__get($key);
    }

    /**
     * Check if profile data attribute isset
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return !is_null($this->getAttribute('data')[$key] ?? null) || parent::__isset($key);
    }

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope for information
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeInformation($query)
    {
        return $query->where('type', 'information');
    }

    /**
     * Query scope for preparation
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePreparation($query)
    {
        return $query->where('type', 'preparation');
    }

    /**
     * Query scope for awards
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAwards($query)
    {
        return $query->where('type', 'awards');
    }

    /**
     * Query scope for patents
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePatents($query)
    {
        return $query->where('type', 'patents');
    }

    /**
     * Query scope for
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAreas($query)
    {
        return $query->where('type', 'areas');
    }

    /**
     * Query scope for activities
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeActivities($query)
    {
        return $query->where('type', 'activities');
    }

    /**
     * Query scope for news
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    /**
     * Query scope for appointments
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAppointments($query)
    {
        return $query->where('type', 'appointments')->orderby('data->start_date', 'DESC')->orderby('sort_order', 'DESC');
    }

    /**
     * Query scope for publications
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePublications($query)
    {
        return $query->where('type', 'publications')->orderby('data->year', 'DESC')->orderby('sort_order', 'DESC');
    }

    /**
     * Query scope for affiliations
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAffiliations($query)
    {
        return $query->where('type', 'affiliations');
    }

    /**
     * Query scope for support
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeSupport($query)
    {
        return $query->where('type', 'support');
    }

    /**
     * Query scope for projects
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeProjects($query)
    {
        return $query->where('type', 'projects');
    }

    /**
     * Query scope for presentations
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePresentations($query)
    {
        return $query->where('type', 'presentations');
    }

    /**
     * Query scope for additionals
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdditionals($query)
    {
        return $query->where('type', 'additionals');
    }

    public function scopePublic($query){
        return $query->where('public', 1);
    }

    ///////////////
    // Relations //
    ///////////////

    /**
     * This belongs to one profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

}
