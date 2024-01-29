<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Experiment;
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
        
        $religion = ["islam", "kristen", "hindu", "budha", "konghucu"];
        for ($i=0; $i < 100; $i++) { 
            Experiment::create([
                "name"       => fake()->name,
                "religion"   => $religion[rand(0, 4)],
                "picture"    => "picture-".rand(1,6).".jpg",
                "score"      => rand(1,100),
                "birth_date" => date("Y-m-d"),
                "address"    => "alamat",
                "is_active"  => rand(0,1),
            ]);
        }

        User::factory()->create([
            'name'     => 'Asteri',
            'email'    => 'admin@gmail.com',
            'password' => password_hash("admin123", PASSWORD_BCRYPT),
        ]);

        // for ($i=0; $i < 1000; $i++) { 
        //     Role::create([
        //         "name" => fake()->name().rand(100,999)
        //     ]);
        // }

        // Experiment::factory(1000)->create();

        // pembuatan role
        $role = Role::create(['name' => 'writer']);
        // pembuatan permision
        $permission = Permission::create(['name' => 'edit articles']);

        // mengkoneksikannya
        $role->givePermissionTo($permission);
        $permission->assignRole($role);
    }
}
