<?php
// namespace database\seeds;
use Illuminate\Support\Carbon;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = Carbon::now();

    Author::insert([
      [
        'name' => 'William Shakespeare',
        'updated_at' => $now,
        'created_at' => $now,
      ],
      [
        'name' => 'J. K. Rowling',
        'updated_at' => $now,
        'created_at' => $now,
      ],
      [
        'name' => 'Osei Yaw Ababio ',
        'updated_at' => $now,
        'created_at' => $now,
      ], [
        'name' => 'De Huang',
        'updated_at' => $now,
        'created_at' => $now,
      ], [
        'name' => 'Andreas Weiermann',
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        'name' => 'Richard P. Feynman',
        'updated_at' => $now,
        'created_at' => $now,
      ],
      [
        'name' => 'Judith Schalansky',
        'updated_at' => $now,
        'created_at' => $now,
      ],
      [
        'name' => 'Jared Diamond',
        'updated_at' => $now,
        'created_at' => $now,
      ], [
        'name' => 'Stephen Hawking',
        'updated_at' => $now,
        'created_at' => $now,
      ], [
        'name' => 'Jiaowen Yang',
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        'name' => 'Anonymous',
        'updated_at' => $now,
        'created_at' => $now,
      ]
    ]);
  }
}
