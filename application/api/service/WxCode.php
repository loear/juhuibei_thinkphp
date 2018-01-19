<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/19
 * Time: 15:30
 */

namespace app\api\service;


class WxCode
{
    private $sendUrl = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=%s";
    // private $sendUrl = "https://api.weixin.qq.com/wxa/getwxacode?access_token=%s";

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        $this->sendUrl = sprintf($this->sendUrl, $token);
    }

    public function makeCode($data)
    {
        $reult = curl_post($this->sendUrl, $data);
        if ($reult) {
            $filename = 'images/wx_code/' . date('YmdHis', time()) . rand(1000, 9999) . $data['width'] . 'x' . $data['width'] . '.jpg';
            file_put_contents($filename, $reult);
            return $filename;
        }
        return false;
    }

}