<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\ItemState;


class ItemStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        ItemState::insert([
            [
                'name' => 'Borrowed',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Lost',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Stolen',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'In Shelf',
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [

                'name' => 'Damaged',
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ]);
    }
}
