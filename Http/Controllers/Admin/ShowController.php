<?php

namespace App\Modules\Jukebox\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ParameterBag;

use App\Modules\Jukebox\Models\Song;
use App\Modules\Jukebox\Models\Album;
use App\Modules\Jukebox\Models\Sheet;

class ShowController extends Controller
{
    /**
     * 用于接收节目上传数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function programUpload(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $num = $request->query('num') ?: 0;
        $metaJSON = [];
        $program = json_decode($request->input('program'), true);
        $resource = json_decode($program['musics'], true);

        for ($i = 0; $i < $num; $i++) {
            $music = $resource[$i];
            try {
                $musicObj = Song::findOrFail($music['id']); /** @var Song $musicObj */
            } catch (ModelNotFoundException $e) {
                return response()->json(['Message' => '创建节目失败, 找不到ID为' . $music['id'] . '的歌曲'], 404);
            }
            array_push($metaJSON, $musicObj->load('sheets'));
        }

        $file = $request->file('pic');
        $filename = 'albums/pics/' . $program['name'] . '-' . $program['time'] . '.' . $this->getFileExtension($file);

        if (!Storage::disk('local')->put($filename, file_get_contents($file->getRealPath())))
            return response()->json($file->getErrorMessage());

        $album = new Album();
        $album->setAttribute('album_cover', Storage::url($filename));
        $album->setAttribute('album_name', $program['name']);
        $album->setAttribute('album_author', $program['author']);
        $album->setAttribute('album_size', $program['allSize']);
        $album->setAttribute('sheets_meta', json_encode($metaJSON, JSON_FORCE_OBJECT));
        $album->setAttribute('broadcast_at', $program['time']);

        try {
            $album->saveOrFail();
            $albumID = $album->getAttributeValue('album_id');

            /** @var Song $model */
            foreach ($metaJSON as $model)
                $model->setAttribute('song_album', $albumID)->saveOrFail();
        } catch (Throwable $e) {
            return response()->json(['Message' => '节目上传失败, 请重试'], 500);
        }
    }

    /**
     * 显示已播放的节目列表
     * @return \Illuminate\Http\JsonResponse 
     */
    public function programList()
    {
        return response()->json([
            'page' => 1,
            'perPage' => 10,
            'list' => Album::getRecentlyPlayedAlbumSheets()
            // 跨域请求
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    /**
     * 用于接收歌曲上传数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function songUpload(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        /**
         * 歌曲上传时附带的点歌信息
         *
         * @var array $msgObj
         * @property string singer
         * @property string songName
         */
        $msgObj = (array) json_decode($request->input('songMessage'), true);

        if (!is_array($msgObj))
            return response()->json(['Message' => '信息未提供完整,无法上传'], 403);

        $song = Song::getFullyDescribe($request->query('id') ?: '-1');

        if (empty($song->getAttributes()))
            return response()->json(['Message' => '没有找到对应曲库的歌曲'], 404);

        if (!is_null($song->getAttributeValue('song_reference')))
            return response()->json(['Message' => '该歌曲已经通过审核'], 403);

        $file = $request->file('mis');

        if (is_null($file) || !str_contains($file->getClientMimeType(), 'audio'))
            return response()->json(['Message' => '文件不存在或者文件格式不对, 请重试。'], 403);

        if ($file->getError() !== 0)
            return response()->json(['Message' => $file->getErrorMessage()], 500);

        $filename = 'songs/' . $msgObj['singer'] . '-' . $msgObj['songName'] . '.' . $this->getFileExtension($file);

        if (!Storage::disk('local')->put($filename, file_get_contents($file->getRealPath()))) {
            return response()->json(['Message' => '文件保存失败,请联系服务器管理员'], 500);
        }

        $song->setAttribute('song_reference', Storage::url($filename));
        $song->setAttribute('song_size', $file->getSize());
        $song->setAttribute('uploader', 'admin'); // TODO 增加登录之后替换为用户名
        $song->sheets()->update(['played' => 1]);
        $song->saveOrFail();

        return response()->json(['Message' => '该歌曲成功通过审核'], 200);
    }

    /**
     * 显示未采纳的歌曲列表(资源未上传)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function songList(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        // 用于排序的条件数组
        $conditions = [];

        if ($request->query->has('apply')) {
            $flag = false;

            switch ($request->query('apply')) {
                case 'yes':
                    $flag = !$flag;
                case 'no':
                    array_push($conditions, ['played', '=', intval($flag)]);
                default:
                    break;
            }
        }

        if (!is_null($days = $request->query('days', null)) && is_numeric($days))
            array_push($conditions, ['created_at', '>=', Carbon::today()->subDays(intval($days))]);

        $orderBy = $request->query('type') == 'hots' ? 'likes' : 'created_at';

        return response()->json([
            'page' => 1,
            'perPage' => 10,
            'list' => Sheet::getRecentlyFailedSongSheets($request->query('page') ?: 1, $conditions, $orderBy)
            // 跨域请求
        ], 200);
    }

    /**
     * 获取文件后缀信息
     *
     * @param UploadedFile $file
     * @return string
     */
    private function getFileExtension(UploadedFile $file)
    {
        return (empty($file->getExtension()) ? mb_substr($file->getClientOriginalName(), mb_strpos($file->getClientOriginalName(), '.') + 1) : $file->getExtension());
    }
}
