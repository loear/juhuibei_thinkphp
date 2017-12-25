<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 15:23
 */

namespace app\lib\exception;


class ImageMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Image不存在';
    public $errorCode = 40000;

}