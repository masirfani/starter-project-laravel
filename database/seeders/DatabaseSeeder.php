<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name'     => 'Asteri',
            'email'    => 'admin@gmail.com',
            'password' => password_hash("admin123", PASSWORD_BCRYPT),
        ]);

        // pembuatan role
        $role = Role::create(['name' => 'writer']);
        // pembuatan permision
        $permission = Permission::create(['name' => 'edit articles']);

        // mengkoneksikannya
        $role->givePermissionTo($permission);
        $permission->assignRole($role);
    }
}
