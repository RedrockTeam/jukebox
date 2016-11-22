<?php

use Illuminate\Database\Seeder;

class SongSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 值得注意的是,relation类调用时只能使用query builder的方法
         */
        factory(\App\Sheet::class, 10)->create()->each(function ($sheet) {
            $sheet->where('song_id', $sheet->song_id)->update(App\Song::getShortDescribe($sheet->song_id)->toArray());

            $user = App\Weixin_User::findOrFail($sheet->user_id)->toArray();

            $sheet->where('user_id', $sheet->user_id)->update(['name' => $user['user_nickname']]);
        });
    }
}
