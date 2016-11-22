<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string    album_author
 * @property string    album_name
 * @property double    album_size
 * @property \DateTime broadcast_at
 * @property string    album_cover
 * @property mixed     sheets_meta
 */
class Album extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'albums';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'album_id';

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $dates = ['published_at'];

    /**
     * @inheritDoc
     */
    protected $casts = ['sheets_meta' => 'array'];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected static $page = 15;

    /**
     * 获取以发布时间排序的节目列表
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getFullyAlbumSheets()
    {
        return self::orderBy('broadcast_at', 'desc')->get([
            'album_id', 'album_name', 'album_author', 'album_cover', 'broadcast_at'
        ]);

    }

    /**
     * 获取指定ID的节目具体描述
     *
     * @param string|integer $album_id
     *
     * @return \App\Album;
     */
    public static function getCurrentAlbumFullyDescribe($album_id)
    {
        return self::findOrFail($album_id);
    }

    /**
     * 获取已经播放过的节目列表
     *
     * @param int $page
     *
     * @return
     */
    public static function getRecentlyPlayedAlbumSheets($page = 1)
    {
        $that = (new self); $that->setPerPage(self::$page);

        return $that->where('broadcast_at', '<', date('Y-m-d H:i:s'))->orderBy('broadcast_at', 'desc')
            ->skip(($page - 1) * $that->getPerPage())
            ->take($that->getPerPage())->get();
    }
}
