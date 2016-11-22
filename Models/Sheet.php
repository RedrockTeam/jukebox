<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'song_sheets';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'sheet_id';

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $dates = ['published_at'];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected static $page = 15;

    /**
     * 定义歌单与歌曲之间的一对一关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function songs()
    {
        return $this->belongsTo(Song::class, 'song_id', 'song_id');
    }

    /**
     * 定义用户与歌单之间的一对一关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function sheets()
    {
        return $this->belongsTo(WeixinUser::class, 'user_id', 'user_id');
    }

    /**
     * 获取某个用户对于某首歌曲的点赞数
     *
     * @param integer $song_id
     * @param integer $user_id
     *
     * @return integer
     */
    public static function getUserVotesWithUserID($song_id, $user_id)
    {
        return Redis::command('hget', ['jk_upvote:' . $song_id . ':userLIST', $user_id]);
    }

    /**
     * 获取以发布时间排列的歌单列表
     *
     * @param int    $page
     * @param array  $conditions
     * @param string $orderBy
     * @param array  $scope
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     * @deprecated
     */
    public static function getRecentlySongSheets($page = 1, $conditions = [], $orderBy = 'created_at', $scope = ['*'])
    {
        /** 设置单页数目 */
        self::$page = 10;

        /** @var \Illuminate\Database\Query\Builder $query */
        $query = self::query()->toBase()->rightJoin('songs', 'songs.song_id', '=', 'song_sheets.song_id');

        if (!empty($conditions)) $query->where($conditions);

        return
            $query
                ->orderBy($orderBy, 'desc')
                ->skip(($page - 1) * self::$page)
                ->take(self::$page)
                ->get($scope);
    }

    /**
     * 获取所有未通过采纳的歌曲列表
     *
     * @param int    $page
     * @param array  $conditions
     * @param string $orderBy
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @deprecated
     */
    public static function getRecentlyFailedSongSheets($page = 1, array $conditions, $orderBy)
    {
        return self::getRecentlySongSheets(
            $page, $conditions, $orderBy,
            [
                'song_sheets.song_id', 'song_sheets.song_name', 'song_sheets.song_singer', 'song_size',
                'name','receiver', 'message',
                'created_at', 'likes', 'played'
            ]
        );
    }

    /**
     * 获取指定ID的用户所发布的歌单
     *
     * @param string|integer $user_id
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getCurrentUserPublishedSheets($user_id)
    {
        return self::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
    }

    /**
     * 获取全部点播过该指定ID的歌曲的列表
     *
     * @param string|integer $song_id
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getUsersSubscribedCurrentSong($song_id)
    {
        return self::with('sheets')->where('song_id', $song_id)->orderBy('created_at', 'desc')->get();
    }

    /**
     * 发布一条新的歌单
     *
     * @param   array $wish
     * @return  void|bool
     */
    public static function storeNewSongSheet(array $wish)
    {
        // 释放歌曲详细信息和用户信息
        $song = $wish['song']->toArray();

        // 插入一条新的歌单在数据库里
        $status = self::query()->toBase()->insert(array_merge($song, array_except($wish, ['song'])));

        return $status;
    }

    /**
     * 保存想听的点击数到指定的歌单里
     *
     * @param   int $id    歌单ID
     * @param   int $votes 点击数
     *
     * @return  mixed
     */
    public static function storeUpVoteNumber($id, $votes)
    {
        return self::where('song_id', $id)->update(['likes' => $votes]);
    }
}
