<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:38
 */

namespace app\lib\exception;


class GameException extends BaseException
{
    public $code = 404;
    public $msg = '指定的游戏不存在，请检查参数';
    public $errorCode = 50000;
}