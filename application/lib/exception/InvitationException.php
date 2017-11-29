<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/25
 * Time: 8:55
 */

namespace app\lib\exception;


class InvitationException extends BaseException
{
    public $code = 503;
    public $msg = '服务器异常';
    public $errorCode = 50000;

}