<?php 

/******************
 * Routes for
 * Testing
 ******************/

Route::name('testing.')->prefix('/testing')->middleware(['auth'])->group(function () {

    Route::name('roles.')->prefix('/roles')->group(function () {

        Route::name('add')->get('{name}/add', function ($name) {
            $role = \App\Role::whereName($name)->firstOrFail();
            auth()->user()->attachRole($role);
            return back()->with(['flash_message' => 'You now have the role: ' . $role->display_name]);
        });

        Route::name('remove')->get('{name}/remove', function ($name) {
            $role = \App\Role::whereName($name)->firstOrFail();
            auth()->user()->detachRole($role);
            return back()->with(['flash_message' => 'You no longer have the role: ' . $role->display_name]);
        });

    });

    Route::name('login_as.')->prefix('/login_as')->group(function () {

        Route::name('select')->get('/', function () {
            $users = \App\User::orderBy('lastname')->get();
            $output = "<h1>Log in as a Different User</h1>\n";
            $output .= "<ul>\n";
            foreach ($users as $user) {
                $url = route('testing.login_as.login', ['id' => $user->id]);
                $output .= "<li><a href='{$url}'>{$user->display_name}</a></li>\n";
            }
            $output .= "</ul>";
            return $output;
        });

        Route::name('login')->get('/{id}', function ($id) {
            auth()->loginUsingId($id);
            return redirect('/');
        });
    });
});
