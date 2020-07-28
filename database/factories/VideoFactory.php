<?php

use Faker\Generator as Faker;

$factory->define(App\Video::class, function (Faker $faker) {
    return [
        'title' => $faker->words( 3, true ),
        'description' => $faker->sentence(),
        'image' => '',
        'video_path' => '',
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
