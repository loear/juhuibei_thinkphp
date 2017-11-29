<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/7/16
 * Time: 23:20
 */

namespace app\common\model;


class User extends BaseModel
{
    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    public static function getByOpenId($openid)
    {
        $user = self::where('openid', '=', $openid)
            ->find();
        return $user;
    }


}