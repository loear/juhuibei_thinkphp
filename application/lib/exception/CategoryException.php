<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/22
 * Time: 7:55
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定的类目不存在，请检查参数';
    public $errorCode = 50000;
}