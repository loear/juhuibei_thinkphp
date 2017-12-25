<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/10
 * Time: 0:06
 */

namespace app\api\validate;


class ActivitySave extends BaseValidate
{
    protected $rule = [
        'user_id'       =>  'require|isPositiveInteger',
        'title'         =>  'require|isNotEmpty',
        'gourmet_title' =>  'require|isNotEmpty',
        'numbers'       =>  'require|isPositiveInteger',
        'image_id'      =>  'require|isPositiveInteger',
        'username'      =>  'require|isNotEmpty',
        'phone'         =>  'require|isMobile',
    ];
    protected $message = [
        'user_id'       =>  '用户ID是正整数',
        'title'         =>  '标题不能为空',
        'gourmet_title' =>  '没有定位',
        'numbers'       =>  '人数上限是正整数',
        'image_id'      =>  '图片ID是正整数',
        'username'      =>  '用户名不能为空',
        'phone'         =>  '手机格式不正确',
    ];

}