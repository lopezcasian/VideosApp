<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIncrementableIdToUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        // Delete foreign keys
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign( ['user_id'] );
            $table->dropForeign( ['video_id'] );
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign( ['user_id'] );
        }); 
        //

        // Change column type
        Schema::table('users', function (Blueprint $table) {
            $table->string( 'id', 36 )->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string( 'id', 36 )->change();
            $table->string( 'video_id', 36 )->change();
            $table->string( 'user_id', 36 )->change();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string( 'id', 36 )->change();
            $table->string( 'user_id', 36 )->change();
        });
        //

        // Add foreign keys
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('video_id')->references('id')->on('videos');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        //

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration cannot be rollback.
    }
}
