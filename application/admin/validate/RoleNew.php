<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/3
 * Time: 9:50
 */

namespace app\admin\validate;


class RoleNew extends BaseValidate
{
    protected $rule = [
        'name' =>  'require|isNotEmpty',
    ];

    protected $message = [
        'name'    =>  '名称不能为空',
    ];

}