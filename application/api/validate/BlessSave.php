<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/25
 * Time: 11:47
 */

namespace app\api\validate;


class BlessSave extends BaseValidate
{
    protected $rule = [
        'form_id'       =>  'require|isNotEmpty',
        'nickname'      =>  'require|isNotEmpty',
        'content'       =>  'require|isNotEmpty',
        'card_id'       =>  'require|isPositiveInteger',
        'cate_id'       =>  'require|isPositiveInteger',

    ];
    protected $message = [
        'form_id'       =>  'form_id不能为空',
        'nickname'      =>  '昵称不能为空',
        'content'       =>  '祝福不能为空',
        'card_id'       =>  'card_id是正整数',
        'cate_id'       =>  'card_id是正整数',
    ];
}