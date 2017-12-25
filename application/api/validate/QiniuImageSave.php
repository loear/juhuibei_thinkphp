<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 14:38
 */

namespace app\api\validate;


class QiniuImageSave extends BaseValidate
{
    protected $rule = [
        'url'  =>  'require|isNotEmpty'
    ];

    protected $message = [
        'url'  =>  '没有url不能保存图片'
    ];

}