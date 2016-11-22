<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeixinUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weixin_users', function (Blueprint $table) {
            $table->increments('user_id');

            /* weixin {{ */
            $table->string('user_openid', 28);
            $table->string('user_avatar');
            $table->string('user_nickname', 40);
            /* }} */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('weixin_users');
    }
}
