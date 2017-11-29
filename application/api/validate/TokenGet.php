<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/7/16
 * Time: 23:16
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code'  =>  'require|isNotEmpty'
    ];

    protected $message = [
        'code'  =>  '没有code不能获取Token'
    ];

}