<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 9:55
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}