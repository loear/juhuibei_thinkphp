<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/25
 * Time: 8:07
 */

namespace app\lib\exception;


class ArticleMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Article不存在';
    public $errorCode = 40000;

}