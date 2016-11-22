<?php

namespace App\Modules\Jukebox\Http\Controllers\Home;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Jukebox\Models\Album;
use App\Modules\Jukebox\Models\Sheet;
use App\Modules\Jukebox\Models\Represent;
use App\Modules\Jukebox\Models\WeixinUser;

class IndexController extends Controller
{
    /**
     * 默认的歌单列表内容
     *
     * @var array
     */
    protected $emptyLists = [
        'sheet_id'      => '-1',
        'song_name'     => '暂无发布歌曲',
        'song_singer'   => '',
        'song_cover'    => '',
        'name'          => 'System',
        'receiver'      => '',
        'message'       => '',
    ];

    /**
     * 默认的公告栏内容
     *
     * @var array
     */
    protected $emptyRepresent = [
        'represent_id'      => '-1',
        'represent_title'   => '暂无公告',
        'announcement'      => '',
        'updated_at'        => '',
    ];

    /**
     * 默认的节目内容
     *
     * @var array
     */
    protected $emptyAlbums = [
        'album_id'      => '-1',
        'album_name'    => '暂无新节目',
        'album_author'  => '',
        'album_cover'   => '',
        'broadcast_at'  => '',
    ];

    /**
     * 获取最新的公告栏
     *
     * @return array
     */
    private function obtainRepresent() {
        $new = Represent::getNewestAnnouncement();

        return is_null($new) ? $this->emptyRepresent : $new->toArray();
    }

    /**
     * 获取以发布时间排序的歌曲列表
     *
     * @param int $page 当前分页数
     * @param     $user
     * @return array
     */
    private function obtainSongsListOrderByPublished($page, $user) {
        $publish = Sheet::getRecentlySongSheets($page);

        foreach ($publish as &$song) {
            $song = (array) $song;
            $song['wts-lst'] = Sheet::getUserVotesWithUserID($song['song_id'], $user);
        }

        return is_null($publish) ? [] : $publish;
    }

    /**
     * 获取以发布时间为准的节目列表
     *
     * @return array
     */
    private function obtainAlbumsOrderByPublished() {
        $albums = Album::getFullyAlbumSheets();

        return is_null($albums) ? $this->emptyAlbums : $albums;
    }

    /**
     * 获取当前用户的个人信息
     *
     * @param Request           $request
     * @param string|integer    $user_id
     * @return array
     */
    private function obtainCurrentUserInfo($request, $user_id) {
        return value(WeixinUser::getFullyUserInfo($user_id));
    }

    /**
     * 获取当前用户发布的歌单
     *
     * @param Request           $request
     * @param string|integer    $user_id
     * @return array
     */
    private function obtainSongsListPublishedByUser($request, $user_id) {
        $publishByUser = Sheet::getCurrentUserPublishedSheets($user_id);

        return is_null($publishByUser) ? $this->emptyLists : $publishByUser;
    }

    public function index(Request $request) {

        $user = $request->session()->get('jukebox.user');
        $page = $request->get('pageNumber');

        if (is_null($page))
            return view('jukebox::index', [
                'announcement'  => $this->obtainRepresent(),
                'songs'         => $this->obtainSongsListOrderByPublished(1, $user),
                'albums'        => $this->obtainAlbumsOrderByPublished(),
                'person'        => [
                    'info'  => $this->obtainCurrentUserInfo($request, $user),
                    'list'  => $this->obtainSongsListPublishedByUser($request, $user)
                ]
            ]);

        return response()->json($this->obtainSongsListOrderByPublished($page, $user));
    }
}
