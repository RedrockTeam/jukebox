<?php

namespace App\Modules\Jukebox\Http\Controllers\Home;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Cache\RateLimiter;

use App\Jobs\Jukebox\UpVote;
use App\Modules\Jukebox\Models\Sheet;
use App\Modules\Jukebox\Models\Song;
use App\Modules\Jukebox\Models\WeixinUser;
use App\Modules\Jukebox\Http\Requests\MusicRequest;

class MusicController extends Controller
{
    /**
     * 显示具体歌曲点播台
     *
     * @param Request        $request
     * @param string|integer $song_id
     *
     * @return $this
     */
    public function detail(Request $request, $song_id)
    {
        return view('jukebox::music.detail', [
            'describe' => $this->obtainCurrentMusicDescribe($song_id),
            'comments' => $this->obtainCurrentMusicComments($song_id)
        ]);
    }

    /**
     * 获取当前ID歌曲的详细描述
     *
     * @param   string|integer $song_id
     *
     * @return  array
     */
    private function obtainCurrentMusicDescribe($song_id)
    {
        return Song::getFullyDescribe(intval($song_id));
    }

    /**
     * 获取当前ID歌曲的留言列表
     *
     * @param   string|integer $song_id
     *
     * @return  array
     */
    private function obtainCurrentMusicComments($song_id)
    {
        return Sheet::getUsersSubscribedCurrentSong(intval($song_id));
    }

    /**
     * 用户希望点击当前歌曲
     *
     * @param MusicRequest|Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wish(MusicRequest $request)
    {
        $data = [
            'flag' => $request->json('flag') ?: true, // 是否为匿名发送
            'user' => $request->session()->get('jukebox.user')
        ];

        // 根据用户的匿名状态获取对应的用户信息
        $wish = WeixinUser::getShortlyUserInfoByUser($this->isAnonymity($data));

        return $this->promiseUserWish($request, $wish);
    }

    /**
     * 承诺用户所期望的点歌要求
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $wish
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function promiseUserWish($request, array $wish)
    {
        // 可以点击某首已经点播过的歌曲, 要求能够获取到歌曲ID
        // 根据referer拿到当前歌曲的ID, 匹配的数字放入到数组下标为1的项
        preg_match('/\/(\d+)$/', $request->header('Referer'), $song);

        if (empty($song)) {
            /** 此处接收的是JSON格式 */
            $msg = \Illuminate\Support\Arr::only($request->json()->all(), ['receiver', 'message']);
            /* 保存新的点歌信息 */
            $song = Song::storeNewSong($request->json('songName'), $request->json('singerName'));
        } else if (is_numeric($song[1])) {
            /** 此处接收的是FORM-DATA */
            $msg = $request->only(['receiver', 'message']);

            $song = intval($song[1]);
        } else
            /* 既不为空,也找不到对应歌曲ID就直接报错 */
            abort(405);

        return $this->writeUserWish($wish, $song, $msg);
    }

    /**
     * 生成用户的愿望
     *
     * @param array $wish 用户愿望
     * @param       $song 点歌ID
     * @param array $msg  用户发送的消息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function writeUserWish(array $wish, $song, array $msg)
    {
        // 找到对应歌曲的简单信息,作为歌单的一部分
        $wish['song'] = $this->obtainCurrentMusicShortDescribe($song);

        if (!$this->achieveUserWish(array_merge($msg, $wish))) return response()->json(['status' => 0]);

        return response()->json(['status' => 200]);
    }

    /**
     * 如果用户是匿名的返回true
     * 否则想办法拿到用户的个人信息
     *
     * @param boolean $status
     *
     * @return integer
     */
    private function isAnonymity($status)
    {
        return false === $status['flag'] ? -1 : $status['user'];
    }

    /**
     * 获取当前ID歌曲的精简描述
     *
     * @param   string|integer $song_id
     * @return  array
     */
    private function obtainCurrentMusicShortDescribe($song_id)
    {
        return Song::getShortDescribe(intval($song_id));
    }

    /**
     * 实现用户所期望的点歌要求
     *
     * @param array $wish
     * @return bool|void
     */
    private function achieveUserWish(array $wish)
    {
        // 发布歌单
        return Sheet::storeNewSongSheet($wish);
    }

    /**
     * 用户想听当前歌曲
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function like(Request $request)
    {
        // 得到指定的ID的歌单
        $sheet = Sheet::where('song_id', $request->get('id'))->first();
        // 得到当前用户的ID
        $user = $request->session()->get('jukebox.user', null);

        // 用于验证唯一会话
        $key = $request->get('id') . '|' . $user;
        // 如果尝试次数不超过3
        if (!app(RateLimiter::class)->tooManyAttempts($key, 3, 5)) {
            app(RateLimiter::class)->hit($key);
        } else {
            return response()->json(['status' => 0]);
        }

        if (is_null($user)) abort(403);

        return $this->promiseUserLike($sheet, $user);
    }

    /**
     * 承诺用户所期望的想听要求
     *
     * @param Sheet $sheet
     * @param int   $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function promiseUserLike(Sheet $sheet, $user)
    {
        $this->dispatch(new UpVote($sheet, $user));

        return response()->json(['status' => 1]);
    }
}
