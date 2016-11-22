<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string represent_title
 * @property string announcement
 */
class Represent extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'represents';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'represent_id';

    /**
     * @inheritDoc
     */
    protected $fillable = ['represent_title', 'annoucement'];

    /**
     * 得到完整的公告栏列表内容
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getBoardList() {
        return self::all()->sortByDesc(function ($announce) {
            return $announce['updated_at']->timestamp;
        });
    }

    /**
     * 得到最新的一条公告栏列表内容
     * @return $this
     */
    public static function getNewestAnnouncement() {
        return self::orderBy('updated_at', 'desc')->first();
    }

    /**
     * 获取指定ID的公告栏内容
     *
     * @param string|integer    $represent_id
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getCurrentAnnouncement($represent_id) {
        if (!is_null($represent_id)) {
            return self::findOrFail(intval($represent_id));
        }

        return self::getNewestAnnouncement();
    }

    /**
     * 设置新公告的标题和内容
     * @param string $title   
     * @param string $content
     * @return int
     */
    public static function setAnnouncement($title, $content)
    {
        return self::insertGetId([
            'represent_title' => $title,
            'annoucement' => $content
        ]);
    }
}
