<?php
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //creates 10 users
    factory(App\User::class, 10)->create();
  }
}
