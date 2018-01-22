<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:16
 */

namespace app\api\validate;


class CardCreate extends BaseValidate
{
    protected $rule = [
        'user_id'       =>  'require|isPositiveInteger',
        'theme_id'      =>  'require|isPositiveInteger',
    ];
    protected $message = [
        'user_id'       =>  '用户ID是正整数',
        'theme_id'      =>  '主题ID是正整数',
    ];

}