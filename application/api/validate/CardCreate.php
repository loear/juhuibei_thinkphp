<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:16
 */

namespace app\api\validate;


class CardCreate extends BaseValidate
{
    protected $rule = [
        'theme_id'      =>  'require|isPositiveInteger',
    ];
    protected $message = [
        'theme_id'      =>  '主题ID是正整数',
    ];

}