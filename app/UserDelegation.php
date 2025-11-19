<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as HasAudits;
use OwenIt\Auditing\Contracts\Auditable;

class UserDelegation extends Pivot implements Auditable
{
    use HasAudits;

    /** @var bool Indicates if the id is auto-incrementing */
    public $incrementing = true;

    /** @var string The database table used by the model */
    protected $table = 'user_delegations';

    /** @var array attribute casting for additional pivot columns */
    protected $casts = [
        'starting' => 'datetime',
        'until' => 'datetime',
    ];

    /** @var array relations to always eager load */
    protected $with = [
        'delegator',
        'delegate',
    ];

    /** @var array default User fields to search */
    protected $user_search_fields = [
        'display_name',
        'name',
        'firstname',
        'lastname',
        'pea',
    ];

    /**
     * Modify the audit data
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformAudit(array $data): array
    {
        // The detach model event doesn't properly include the pivot model id,
        // so we need this workaround for now.
        $data['auditable_id'] = $data['auditable_id'] ?? 0;

        return $data;
    }

    public function delegateIs(User $user): bool
    {
        return $this->delegate_user_id === $user->id;
    }

    public function delegatorIs(User $user): bool
    {
        return $this->delegator_user_id === $user->id;
    }

    //////////////////
    // Query Scopes //
    //////////////////

    /**
     * Query scope to search for delegations by user
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search_for the text to search for
     * @param array $search_fields the user fields to search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search_for, $search_fields = [])
    {
        if ($search_for === '') {
            return $query;
        }

        $search_fields = $search_fields ?: $this->user_search_fields;

        $query->whereHas('delegator', function ($search_q) use ($search_for, $search_fields) {
            $search_q->where($search_fields[0], 'LIKE', "%{$search_for}%");
            foreach (array_slice($search_fields, 1) as $field) {
                $search_q->orWhere($field, 'LIKE', "%{$search_for}%");
            }
        });
        $query->orWhereHas('delegate', function ($search_q) use ($search_for, $search_fields) {
            $search_q->where($search_fields[0], 'LIKE', "%{$search_for}%");
            foreach (array_slice($search_fields, 1) as $field) {
                $search_q->orWhere($field, 'LIKE', "%{$search_for}%");
            }
        });

        return $query;
    }

    /**
     * Query scope for delegations that should notify
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool|string $should_notify
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShouldNotify($query, $should_notify = true)
    {
        if ($should_notify === '') {
            return $query;
        }

        return $query->where('gets_reminders', '=', $should_notify);
    }

    ////////////////
    // Relations  //
    ////////////////

    public function delegator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegator_user_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegate_user_id');
    }
}
