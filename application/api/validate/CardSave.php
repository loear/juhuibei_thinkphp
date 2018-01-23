<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/1/23
 * Time: 22:30
 */

namespace app\api\validate;


class CardSave extends BaseValidate
{
    protected $rule = [
        'card_id'          =>  'require|isPositiveInteger',
        'bride_name'       =>  'require|isNotEmpty',
        'bride_phone'      =>  'isMobile',
        'bridegroom_name'  =>  'require|isNotEmpty',
        'bridegroom_phone' =>  'isMobile',
    ];
    protected $message = [
        'card_id'          =>  '请柬ID是正整数',
        'bride_name'       =>  '新娘姓名不能为空',
        'bride_phone'      =>  '请输入的手机号不正确',
        'bridegroom_name'  =>  '新郎姓名不能为空',
        'bridegroom_phone' =>  '请输入的手机号不正确',
    ];

}