<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:21
 */

namespace app\lib\exception;


class CreateCardException extends BaseException
{
    public $code = 404;
    public $msg = '创建失败，请检查UserId和ThemeId';
    public $errorCode = 80000;

}