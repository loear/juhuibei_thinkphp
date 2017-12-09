<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/10
 * Time: 0:06
 */

namespace app\api\validate;


class ActivityPostSubmit extends BaseValidate
{
    protected $rule = [
        'user_id'    =>  'require|isNotEmpty',
    ];
    protected $message = [
        'user_id'    =>  '用户ID不能为空'
    ];

}