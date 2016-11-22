<?php

namespace App\Modules\Jukebox\Http\Middleware;

use Closure;
use App\Modules\Jukebox\Models\WeixinUser;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $weixin = $request->session()->get('weixin.user');

        // 不接受无信用的凭证
        if (empty($weixin['openid']) || strlen($weixin['openid']) !== 28) return abort(405);

        // 假设用户数据已经存在于数据库中
        if (!$request->session()->has('jukebox.user')) {
            $user = WeixinUser::getFullyUserInfoWithOpenID($weixin['openid']);

            if (empty($user)) {
                $user = WeixinUser::storeNewUserInfo((new WeixinUser), $weixin->toArray());
            }

            // 存储当前会话消息
            $request->session()->set('jukebox.user', $user->user_id);
        }

        return $next($request);
    }
}
