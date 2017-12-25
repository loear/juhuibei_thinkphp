<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 14:03
 */

namespace app\api\validate;


class ActivityImageSave extends BaseValidate
{
    protected $rule = [
        'user_id'       =>  'require|isPositiveInteger',
        'image_id'      =>  'require|isPositiveInteger',
        'activity_id'   =>  'require|isPositiveInteger',
    ];
    protected $message = [
        'user_id'       =>  '用户ID是正整数',
        'image_id'      =>  '图片ID是正整数',
        'activity_id'   =>  '聚会ID是正整数',
    ];

}