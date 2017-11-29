<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/3
 * Time: 9:50
 */

namespace app\admin\validate;


class ArticleNew extends BaseValidate
{
    protected $rule = [
        'title' =>  'require|isNotEmpty',
    ];

    protected $message = [
        'title'    =>  '标题不能为空',
    ];

}