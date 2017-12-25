<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 14:25
 */

namespace app\api\validate;


class UserComingSave extends BaseValidate
{
    protected $rule = [
        'user_id'       =>  'require|isPositiveInteger',
        'activity_id'   =>  'require|isPositiveInteger',
        'username'      =>  'require|isNotEmpty',
        'phone'         =>  'require|isMobile',
    ];
    protected $message = [
        'user_id'       =>  '用户ID是正整数',
        'activity_id'   =>  '聚会ID是正整数',
        'username'      =>  '用户名不能为空',
        'phone'         =>  '手机格式不正确',
    ];

}