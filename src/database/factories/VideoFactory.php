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
            $file = UploadedFile::fake()->image( "image_testing.jpg" );
            $storage = new VideoMiniatureStorage( Storage::disk( "images" ) );
            return $storage->save( $file );
        },
        'video_path' => function(){
            $file = UploadedFile::fake()->create( "video_testing.mp4", 10000 );
            $storage = new FileVideoStorage( Storage::disk( 'videos' ) );
            return $storage->save( $file );
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
