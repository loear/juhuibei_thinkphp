<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/31
 * Time: 15:32
 */

namespace app\api\validate;


class JoinWeddingSave extends BaseValidate
{
    protected $rule = [
        'user_id'       =>  'require|isPositiveInteger',
        'card_id'       =>  'require|isPositiveInteger',
        'part_num'      =>  'require|isPositiveInteger',
        'user_name'     =>  'require|isNotEmpty'
    ];
    protected $message = [
        'user_id'       =>  '用户ID是正整数',
        'card_id'       =>  '请柬ID是正整数',
        'part_num'      =>  '人数ID是正整数',
        'user_name'     =>  '用户名不能为空'
    ];

}