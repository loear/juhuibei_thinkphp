<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/1/15
 * Time: 22:58
 */

namespace app\lib\exception;


class ThemeMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Theme不存在';
    public $errorCode = 40000;
}