<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/3
 * Time: 9:50
 */

namespace app\admin\validate;


class NodeNew extends BaseValidate
{
    protected $rule = [
        'node' =>  'require|isNotEmpty',
    ];

    protected $message = [
        'node'    =>  '节点不能为空',
    ];

}