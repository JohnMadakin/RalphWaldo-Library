<?php

use Illuminate\Support\Carbon;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        Category::insert([
            [
                'name' => 'Arts',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Sciences',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Philosophy',
                'updated_at' => $now,
                'created_at' => $now,
            ], [
                'name' => 'Business',
                'updated_at' => $now,
                'created_at' => $now,
            ], [
                'name' => 'Engineering',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ]);
    }
}
