<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    /** @var array attributes that are mass-assignable */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}
