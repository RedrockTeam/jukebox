<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string song_singer
 * @property string song_name
 * @property string receiver
 * @property string song_reference
 * @property int    song_size
 */
class Song extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'songs';

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'song_id';

    /**
     * @inheritDoc
     */
    protected $dates = ['published_at'];

    /**
     * 歌曲的简单描述
     *
     * @var array
     */
    protected static $describe = ['song_id', 'song_name', 'song_singer', 'song_cover'];

    /**
     * 定义歌单与歌曲之间的一对一关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne;
     */
    public function sheets() {
        return $this->hasOne(Sheet::class, 'song_id', 'song_id');
    }

    /**
     * 获取一首歌的完整信息
     *
     * @param null|integer $song_id
     * @return $this;
     */
    public static function getFullyDescribe($song_id = null) {
        return self::find($song_id);
    }

    /**
     * 获取一首歌的简单描述
     *
     * @param integer $song_id
     * @param array $describe
     * @return array;
     */
    public static function getShortDescribe($song_id, $describe = []) {
        return \Illuminate\Support\Collection::make(self::getFullyDescribe($song_id)->toArray())->filter(function ($value, $column) use ($describe) {
            return in_array($column, empty($describe) ? self::$describe : $describe);
        });
    }

    /**
     * 创建一首新歌曲在曲库
     *
     * @param string    $name     歌曲名称
     * @param string    $singer   演唱者
     *
     * @return integer
     */
    public static function storeNewSong($name, $singer) {
        return self::query()->toBase()->insertGetID(['song_name' => $name, 'song_singer' => $singer], 'song_id');
    }
}
