<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

class WeixinUser extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'weixin_users';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'user_id';

    /**
     * @inheritDoc
     */
    protected $hidden = ['user_openid'];

    /**
     * 定义用户与歌单之间的一对一关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne;
     */
    public function sheets() {
        return $this->hasOne(Sheet::class, 'user_id', 'user_id');
    }

    /**
     * 获取指定ID的完整用户信息
     *
     * @param string|integer $user_id
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getFullyUserInfo($user_id) {
        return self::where('user_id', intval($user_id))->first();
    }

    /**
     * 获取指定ID的部分用户信息
     *
     * @param
     * bool $user_id 当前用户ID
     *
     * @return array
     */
    public static function getShortlyUserInfoByUser($user_id) {
        /* 获取用户信息 */
        $info = self::getFullyUserInfo($user_id);

        return
            is_null($info) ? [
                'name' => '匿名',
                'avatar' => '',
                'user_id' => -1
            ] : [
                'name' => $info->user_nickname,
                'avatar' => $info->user_avatar,
                'user_id' => $info->user_id
            ];
    }

    /**
     * 获取指定OpenID的完整用户信息
     *
     * @param string $user_openid
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getFullyUserInfoWithOpenID($user_openid) {
        return self::where('user_openid', $user_openid)->first();
    }

    /**
     * 存储新用户的个人信息
     *
     * @param WeixinUser $user
     * @param array       $weixin
     * @return WeixinUser
     */
    public static function storeNewUserInfo(WeixinUser $user, array $weixin) {

        /*
         * openid, avatar, nickname
         *
         */
        $user->user_openid = $weixin['openid'];
        $user->user_nickname = $weixin['nickname'];
        $user->user_avatar = $weixin['headimgurl'];

        $status = $user->save();

        if (true === $status) return $user;
    }
}
