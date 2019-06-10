<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;


class ReturnItemReportTableSeeder extends Seeder
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
                'name' => 'Borrowed',
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
