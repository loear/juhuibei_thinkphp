<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/3
 * Time: 9:50
 */

namespace app\admin\validate;


class CommentNew extends BaseValidate
{
    protected $rule = [
        'comment' =>  'require|isNotEmpty',
    ];

    protected $message = [
        'comment'    =>  '评论不能为空',
    ];

}