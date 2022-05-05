<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit as AuditTrait;
use OwenIt\Auditing\Contracts\Audit as AuditContract;

class LogEntry extends Model implements AuditContract
{
    use AuditTrait;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Query scope to search audit logs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchFor($query, $search = '')
    {
        return $query->when($search, function ($search_query) use ($search) {
            $search_query
                ->whereRelation('user', 'users.display_name', 'LIKE', "%$search%")
                ->orWhere('event', 'LIKE', "%$search%")
                ->orWhere('auditable_type', 'LIKE', "%$search%")
                ->orWhere('old_values', 'LIKE', "%$search%")
                ->orWhere('new_values', 'LIKE', "%$search%")
                ->orWhere('url', 'LIKE', "%$search%");
        });
    }
}
