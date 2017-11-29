<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/18
 * Time: 15:38
 */

namespace app\admin\validate;


class ImageNew extends BaseValidate
{
    protected $rule = [
        'pid' =>  'require|isPostiveInteger',
    ];

    protected $message = [
        'pid'    =>  'PID大于零',
    ];

    protected function isPostiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) >= 0) {
            return true;
        } else {
            return false;
        }
    }

}