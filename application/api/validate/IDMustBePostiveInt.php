<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 9:51
 */

namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
        'id'    =>  'require|isPositiveInteger',
    ];
    protected $message = [
        'id'    =>  'id必须是正整数'
    ];

}