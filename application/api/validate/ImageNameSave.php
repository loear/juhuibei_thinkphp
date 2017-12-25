<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 14:48
 */

namespace app\api\validate;


class ImageNameSave extends BaseValidate
{
    protected $rule = [
        'image_id'      =>  'require|isPositiveInteger',
        'name'          =>  'require|isNotEmpty',
    ];
    protected $message = [
        'image_id'      =>  '图片ID是正整数',
        'name'          =>  '图片名不能为空',
    ];

}