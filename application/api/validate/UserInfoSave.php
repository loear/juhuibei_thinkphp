<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 11:48
 */

namespace app\api\validate;


class UserInfoSave extends BaseValidate
{
    protected $rule = [
        'user_id'    =>  'require|isPositiveInteger',
        'nickname'   =>  'require|isNotEmpty',
        'avatar_url' =>  'require|isNotEmpty',

    ];
    protected $message = [
        'user_id'    =>  '用户ID是正整数',
        'nickname'   =>  '昵称不能为空',
        'avatar_url' =>  '头像URL不能为空',
    ];

}