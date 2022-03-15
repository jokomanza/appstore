<?php

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\App::class, function (Faker\Generator $faker) {
    static $name;

    $faker->addProvider(new \Xvladqt\Faker\LoremFlickrProvider($faker));

    $name = $faker->unique()->regexify('[a-z]{3,20}$');

    return [
        'name' => $name,
        'package_name' => "com.quick.$name",
        'description' => $faker->unique()->text(),
        'type' => $faker->unique()->word(),
        'icon_url' => $faker->imageUrl(50, 50, ['cats', 'dogs']),
        'repository_url' => $faker->unique()->url,
        'user_documentation_url' => $faker->unique()->url,
        'developer_documentation_url' => $faker->unique()->url,
        'api_token' => str_random(128),
    ];
});
