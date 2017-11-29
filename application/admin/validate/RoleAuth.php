<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/26
 * Time: 15:24
 */

namespace app\admin\validate;


class RoleAuth extends BaseValidate
{
    protected $rule = [
        'id'    =>  'require|isNotEmpty',
        'node'  =>  'require|isNotEmpty',
    ];

    protected $message = [
        'id'    =>  'id不能为空',
        'node'  =>  '请选择节点',
    ];


}