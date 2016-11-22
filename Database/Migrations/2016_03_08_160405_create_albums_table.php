<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('album_id');

            /* albums infomation {{ */
            $table->string('album_name', 50);
            $table->string('album_author', 30);
            $table->string('album_cover');
            $table->integer('album_size')->unsigned();
            /* }} */

            /* json meta {{ */
            $table->text('sheets_meta');
            /* }} */

            $table->timestamp('published_at');
            $table->timestamp('broadcast_at');

            /* 以节目名称作为索引 */
            $table->index('album_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('albums');
    }
}
