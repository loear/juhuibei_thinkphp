<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/7/16
 * Time: 23:51
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    // HTTP 状态码 404, 200
    public $code = 400;

    // 错误具体信息
    public $msg = '微信服务器接口调用失败';

    // 自定义的错误码
    public $errorCode = 999;

    public function __construct($params = [])
    {
        if (! is_array($params)) {
            // throw new \think\Exception();
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }

}