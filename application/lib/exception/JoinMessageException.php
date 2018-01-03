<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 9:45
 */

namespace app\lib\exception;


class JoinMessageException extends BaseException
{
    public $code = 404;
    public $msg = '报名失败，请检查UserId和ActivityId';
    public $errorCode = 80000;
}