<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 15:05
 */

namespace app\lib\exception;


class ActivityMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Activity不存在';
    public $errorCode = 40000;
}