<?php

namespace App\Modules\Jukebox\Http\Controllers\Home;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Jukebox\Models\Album;

class AlbumController extends Controller
{
    /**
     * 获取指定ID的节目内容描述
     *
     * @param string|integer $album_id
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function obtainCurrentAlbumContent($album_id) {
        $album = new Album();

        $content = $album->getCurrentAlbumFullyDescribe(intval($album_id));
        // 将歌单列表反序列化后,加入到内容数组里
        $sheets_meta = $this->obtainSheetMeta($content);

        // 如果没有出现歌单详情,跳转到404界面
        if (empty($sheets_meta)) return redirect('/jukebox/index');

        return ['describe' => $content->toArray(), 'albums' => $sheets_meta];
    }

    /**
     * 反序列化歌单列表,获取其具体内容
     *
     * @param Album $album
     * @return array
     */
    private function obtainSheetMeta(Album $album) {
        return json_decode($album->getAttributeValue('sheets_meta'), true);
    }

    /**
     * 显示节目具体内容
     *
     * @param Request           $request
     * @param string|integer    $album_id
     * @return $this
     */
    public function detail(Request $request, $album_id) {
        // 此时,可以直接输出视图
        return view('jukebox::music.album', $this->obtainCurrentAlbumContent($album_id))->with('count', 1);
    }
}
