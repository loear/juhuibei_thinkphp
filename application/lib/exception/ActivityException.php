<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:27
 */

namespace app\lib\exception;


class ActivityException extends BaseException
{
    public $code = 503;
    public $msg = '服务器异常';
    public $errorCode = 50000;
}