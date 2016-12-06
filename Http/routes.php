<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['prefix' => 'jukebox', 'middleware' => ['weixin.auth', 'jukebox.auth']], function() {

    /* 用户通过ajax传输过来的操作 {{ */
    Route::post('/song/like', 'Home\MusicController@like');
    Route::post('/song/wish', 'Home\MusicController@wish');
    /* }} */

    /* 期望展示的页面 {{ */
    Route::get('/index', 'Home\IndexController@index');
    Route::get('/song/{song_id}', ['as' => 'songs', 'uses' => 'Home\MusicController@detail']);
    Route::get('/album/{album_id}', ['as' => 'albums', 'uses' => 'Home\AlbumController@detail']);
    /* }} */

    Route::get('/dedication', function () { return view('jukebox::music.dedication'); });

});

Route::group(['prefix' => 'jukebox/admin'], function () {
    // 需要显示的页面
    Route::get('/', function () { return view('jukebox::manager.index'); });
    Route::post('/login', 'Admin\AuthController@login');
    // 歌曲管理
    Route::get('/song/list', ['uses' => 'Admin\ShowController@songList']);
    Route::post('/song/upload', ['uses' => 'Admin\ShowController@songUpload']);
    // 节目管理
    Route::get('/program/list', ['uses' => 'Admin\ShowController@programList']);
    Route::post('/program/upload', ['uses' => 'Admin\ShowController@programUpload']);
    // 公告管理
    Route::get('/represent/history', ['uses' => 'Admin\HelpController@representHistory']);
    Route::post('/represent/ruler', ['uses' => 'Admin\HelpController@representRuler']);
    // 违规字提醒
    Route::get('/slang/list', 'Admin\HelpController@slangList');
    Route::post('/slang/add', 'Admin\HelpController@slangAdd');
});