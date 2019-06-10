<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //register the user seeder
        $this->call([
            RoleTableSeeder::class,
            UserTableSeeder::class,
            CategoryTableSeeder::class,
            ItemStateTableSeeder::class,
            ItemTypeTableSeeder::class,
            ReturnItemReportTableSeeder::class
        ]);
        Model::reguard();
    }
}
