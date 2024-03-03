<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Commander',
            'username' => 'commander',
            'password' => bcrypt('password'),
            'military_number' => 0,
            'department_id' => 0,
            'rank_id' => 0
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);

        $role = Role::create(['name' => 'Department Head']);

        \App\Models\Department::create([
            'name' => 'ملاحة'
        ]);

        \App\Models\Department::create([
            'name' => 'إشارة'
        ]);

        $rank_categories = \App\Models\RankCategory::create([
            'name' => 'officer'
        ]);

        $rank_categories = \App\Models\RankCategory::create([
            'name' => 'sub-officer'
        ]);

        

        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Department Head',
        //     'username' => 'departmenthead',
        //     'password' => bcrypt('password'),
        //     'department_id' => 1
        // ]);


        // $user->assignRole($role);

        // \App\Models\User::factory()->create([
        //     'name' => 'Normal',
        //     'username' => 'Normal',
        //     'password' => bcrypt('password'),
        //     'department_id' => 1
        // ]);
    }
}
