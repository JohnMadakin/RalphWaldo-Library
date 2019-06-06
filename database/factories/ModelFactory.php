<?php
use Illuminate\Hashing\BcryptHasher;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $bcrypt = new BcryptHasher();
    return [
        'name' => $faker->name,
        'email' => $faker->email->unique(),
        'userName' => $faker->userName->unique(),
        'address' => $faker->address,
        'name' => $faker->name,
        'password' => $bcrypt->make('12345'),
    ];
});
