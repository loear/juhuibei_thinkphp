<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/17
 * Time: 14:09
 */

namespace app\lib\exception;


class CardMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Card不存在';
    public $errorCode = 40000;

}