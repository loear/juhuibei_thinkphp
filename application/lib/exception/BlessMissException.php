<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/16
 * Time: 17:43
 */

namespace app\lib\exception;


class BlessMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Bless不存在';
    public $errorCode = 40000;

}