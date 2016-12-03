<?php

namespace App\Modules\Jukebox\Http\Controllers\Admin;

use App\Http\Requests;
use App\Modules\Jukebox\Models\BadWord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Jukebox\Models\Represent;

class HelpController extends Controller
{
	/**
	 * 显示历史公告
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function representHistory()
    {
    	return response()->json(Represent::getBoardList(), 200);
    }

    /**
     * 发布新的公告
     * @param  Request $request
     * @return void|\Illuminate\Http\JsonResponse
     */
    public function representRuler(Request $request)
    {
    	$announcement = $request->only(['title', 'content']);

    	if (empty($announcement) || empty($announcement['title']) || empty($announcement['content']))
    		return response()->json(['Message' => '公告信息不完整，请完善后再试'], 403);

    	if (Represent::setAnnouncement($announcement['title'], $announcement['content']) < 0)
    		return response()->json(['Message' => '公告发布失败'], 500);
    }

    /**
     * 显示所有违规字
     * @return \Illuminate\Http\JsonResponse
     */
    public function slangList()
    {
        return response()->json(BadWord::getWords(), 200);
    }

    /***/
    public function slangAdd(Request $request)
    {
        dump($request->all());
    }
}
