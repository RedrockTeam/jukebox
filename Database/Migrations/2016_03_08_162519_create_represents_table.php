<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('represents', function (Blueprint $table) {
            $table->increments('represent_id');

            /* announcement meta {{ */
            $table->string('represent_title', 30);
            $table->mediumText('announcement');
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
        Schema::drop('represents');
    }
}
