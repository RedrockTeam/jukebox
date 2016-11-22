<?php

use Illuminate\Database\Seeder;

class WeixinUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Weixin_User::class, 20)->create();
    }
}
