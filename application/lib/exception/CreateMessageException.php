<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 9:43
 */

namespace app\lib\exception;


class CreateMessageException extends BaseException
{
    public $code = 404;
    public $msg = '创建失败，请检查UserId和ActivityId';
    public $errorCode = 80000;
}