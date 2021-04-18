<?php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Classes\VideoMiniatureStorage;
use App\Classes\FileVideoStorage;

use Faker\Generator as Faker;

$factory->define(App\Video::class, function (Faker $faker) {
    return [
        'title' => $faker->words( 3, true ),
        'description' => $faker->sentence(),
        'image' => function(){
            #TODO: add files to this factory
            return 'test_image';
        },
        'video_path' => function(){
            #TODO: add files to this factory
            return 'test_video';
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'created_at' => $faker->dateTimeBetween('-1 week', 'today')
    ];
});
