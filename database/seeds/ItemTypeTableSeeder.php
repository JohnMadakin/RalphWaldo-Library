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
                'name' => 'books',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'audio cd',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'dvd',
                'updated_at' => $now,
                'created_at' => $now,
            ],[
                'name' => 'booklet',
                'updated_at' => $now,
                'created_at' => $now,
            ],[
                'name' => 'magazine',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ]);

    }
}
