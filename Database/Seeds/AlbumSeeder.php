<?php

use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Album::class, 3)->create()->each(function ($album) {

            $sheet = App\Sheet::Class;
            // 获得十个随机的歌曲,用来组成节目单
            $random = \Illuminate\Support\Collection::make((new $sheet)->get([
                'song_id', 'song_name', 'song_singer', 'name', 'user_id'
            ])->toArray())->random(10);

            // 计算歌单内容大小
            $total_size = 0; $songs = new $sheet;
            foreach(($all = $random->all()) as $id) {
                $song = $songs->where('song_id', '=', $id['song_id'])->first()->songs()->get(['song_size'])->toArray();
                $total_size += $song[0]['song_size'];
            }

            // 序列化节目单
            $meta = json_encode($all, JSON_FORCE_OBJECT);
            // 加入当前节目的meta列
            $album->where('album_id', $album->album_id)->update(['sheets_meta' => $meta, 'album_size' => $total_size]);
        });
    }
}
