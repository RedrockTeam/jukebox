<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyForSong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_sheets', function (Blueprint $table) {
            /* foreign key */
            $table->foreign('song_id')->references('song_id')->on('songs');
            $table->foreign('user_id')->references('user_id')->on('weixin_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
