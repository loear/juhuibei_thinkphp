<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 10:32
 */

namespace app\api\validate;


class CardCoverMake extends BaseValidate
{
    protected $rule = [
        'card_id'       =>  'require|isPositiveInteger',
        'theme_id'      =>  'require|isPositiveInteger',
    ];
    protected $message = [
        'card_id'       =>  '请柬ID是正整数',
        'theme_id'      =>  '主题ID是正整数',
    ];


}