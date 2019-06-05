<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Role::class, 2)->create();
    }
}
