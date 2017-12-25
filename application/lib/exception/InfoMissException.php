<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 15:18
 */

namespace app\lib\exception;


class InfoMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Info不存在';
    public $errorCode = 40000;

}