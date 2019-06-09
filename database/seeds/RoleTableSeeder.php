<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Carbon;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        Role::insert([
            [
                'role' => 'Admin',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'role' => 'Super Admin',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'role' => 'Librarian',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'role' => 'User',
                'updated_at' => $now,
                'created_at' => $now,
            ],

        ]);
    }
}
