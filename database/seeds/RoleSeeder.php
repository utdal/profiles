<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleSeeder extends Seeder {

	/**
	 * Run the Role database seeds
	 * @return void
	 */
	public function run()
	{	
		// Site roles

		$site_admin = Role::create([
			'name' => 'site_admin',
			'display_name' => 'Site Administrator',
			'description' => 'User is allowed to do everything'
		]);

		// UTD roles
		 
		$dean = Role::create([
			'name' => 'dean',
			'display_name' => 'Dean',
			'description' => 'User is a Dean'
		]);

		$staff = Role::create([
			'name' => 'staff',
			'display_name' => 'Staff',
			'description' => 'User is staff'
		]);

		$student = Role::create([
			'name' => 'student',
			'display_name' => 'Student',
			'description' => 'User is student'
		]);

		$faculty = Role::create([
			'name' => 'faculty',
			'display_name' => 'Faculty',
			'description' => 'User is faculty'
		]);

		$directory = Role::create([
			'name' => 'directory',
			'display_name' => 'Directory',
			'description' => 'User is allowed in the directory'
		]);

		// Profile roles

		$profiles_editor = Role::create([
			'name' => 'profiles_editor',
			'display_name' => 'Profiles Editor',
			'description' => 'User is an editor of any profiles',
		]);
		
		$school_profiles_editor = Role::create([
			'name' => 'school_profiles_editor',
			'display_name' => 'School Profiles Editor',
			'description' => 'User is an editor of profiles for their school',
		]);

		$department_profiles_editor = Role::create([
            'name' => 'department_profiles_editor',
            'display_name' => 'Department Profiles Editor',
            'description' => 'User is an editor of profiles for their department',
        ]);
	
	}
}