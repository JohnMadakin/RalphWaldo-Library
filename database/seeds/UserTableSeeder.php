<?php
use Illuminate\Hashing\BcryptHasher;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $bcrypt = new BcryptHasher();

    //creates 10 users
    factory(App\Models\User::class, 10)->create();
    User::create([
      'userName' => 'test1',
      'password' => $bcrypt->make('password@1'),
      'email' => 'test@test.com',
      'address' => 'test 123 lagos',
      'name' => 'test',
    ])->roles()->attach(2);
  }
}
