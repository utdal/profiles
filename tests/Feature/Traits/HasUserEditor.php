<?php

namespace Tests\Feature\Traits;

use App\Role;
use App\User;

trait HasUserEditor
{
    /**
     * Create a logged-in User editor.
     *
     * @return User
     */
    protected function loginAsUserEditor()
    {
        $editor = factory(User::class)->create();
        $editor->attachRole(Role::whereName('site_admin')->firstOrFail());
        auth()->loginUsingId($editor->id);

        return $editor;
    }
}