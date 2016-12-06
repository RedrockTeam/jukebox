<?php

namespace App\Modules\Jukebox\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController as Controller;

use App\Exceptions\Method\MethodNotFoundException;

class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    protected $redirectTo = '/jukebox/admin/login';

    /**
     * 用户登录帐号
     *
     * @var string
     */
    protected $username = 'username';

    /**
     * 不允许用户验证
     *
     * @inheritdoc
     */
    protected function validator(array $data)
    {
        throw new MethodNotFoundException();
    }

    /**
     * 不允许创建用户
     *
     * @inheritdoc
     */
    protected function create(array $data)
    {
        throw new MethodNotFoundException();
    }

    /**
     * 用户尝试登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);
        } catch (ValidationException $e) {
            return response()->json(['status' => '请求参数不完整，无法登录'], 403);
        }

        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && ($isLocked = $this->hasTooManyLoginAttempts($request))) {
            $this->fireLockoutEvent($request);

            return response()->json(
                [
                    'status' => '登录尝试次数过多, 请' . $this->secondsRemainingOnLockout($request) . '秒钟后进行登录操作'
                ],
                403
            );
        }

        $user = $request->input($this->username);
        $credentials = $this->getCredentials($request);

        if (empty($password = env('USER_' . strtoupper($user), '')) || $credentials != $password) {
            if ($throttles && !$isLocked) $this->incrementLoginAttempts($request);
            return response()->json(['status' => '登录失败, 用户名或密码错误', 403]);
        }

        if ($throttles) $this->clearLoginAttempts($request);

        $request->session()->set('user_' . strtolower($user), password_hash(substr($credentials, 5), PASSWORD_BCRYPT));
        $request->session()->migrate(true);

        return response()->json(['status' => '登录成功'], 200);
    }
}
