<?php

use Illuminate\Support\Carbon;

use Illuminate\Database\Seeder;
use App\Models\ItemsType;

class ItemTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        ItemsType::insert([
            [
                'name' => 'Books',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Audio CD',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'DVD',
                'updated_at' => $now,
                'created_at' => $now,
            ],[
                'name' => 'Booklet',
                'updated_at' => $now,
                'created_at' => $now,
            ],[
                'name' => 'Magazine',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ]);

    }
}
