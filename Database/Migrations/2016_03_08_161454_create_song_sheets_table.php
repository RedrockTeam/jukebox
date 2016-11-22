<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_sheets', function (Blueprint $table) {
            $table->increments('sheet_id');

            /* reference song info {{ */
            $table->integer('song_id')->unsigned();
            $table->string('song_name', 60);
            $table->string('song_singer', 60);
            $table->string('song_cover');
            /* }} */

            /* reference user info */
            $table->string('name', 40);
            $table->integer('user_id')->unsigned();
            /* }} */

            /* sheet context {{ */
            $table->string('receiver', 30);
            $table->mediumText('message');
            /* }} */

            $table->integer('likes')->default(0);
            $table->boolean('played')->default(false);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('song_sheets');
    }
}
