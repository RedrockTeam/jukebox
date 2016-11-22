<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->increments('song_id');

            /* songs infomation {{*/
            $table->string('song_name', 60);
            $table->string('song_album', 60);
            $table->string('song_singer', 60);
            $table->string('song_cover');
            $table->string('song_link');
            $table->string('song_reference'); // 考虑到外部引用的音乐地址
            $table->integer('song_size')->unsigned(); // 音乐文件大小,用整型表示
            $table->string('uploader', 15); // 上传者
            /* }} */

            $table->timestamp('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('songs');
    }
}
