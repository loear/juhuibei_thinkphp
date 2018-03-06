<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 14:03
 */

namespace app\home\controller;


use app\home\service\Ticket;
use think\Controller;

class Games extends Controller
{
    public function readHeart()
    {
        return view();
    }

    public function dontDie()
    {
        return view();
    }

    // jsapi_ticket是公众号用于调用微信JS接口的临时票据。正常情况下，jsapi_ticket的有效期为7200秒，通过access_token来获取。由于获取jsapi_ticket的api调用次数非常有限，频繁刷新jsapi_ticket会导致api调用受限，影响自身业务，开发者必须在自己的服务全局缓存jsapi_ticket 。
    public function crop()
    {
        $appId = config('wx.app_id');;
        $timestamp = time();
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $nonceStr = substr(str_shuffle($str), rand(0, 35), 16); // 16位随机字符 ｜ 62 - 16 - 1 = 35
        $ticket_model = new Ticket();
        $jsapi_ticket = $ticket_model->getTicket();
        echo $jsapi_ticket;
        echo '++', $nonceStr;
        $string1_arr = [
            'jsapi_ticket' => $jsapi_ticket,
            'noncestr'     => $nonceStr,
            'timestamp'    => $timestamp
        ];
        $string1 = http_build_query($string1_arr) . '&url=https://www.juhuibei.com/play/crop.html';
        $signature = sha1($string1);

        $this->assign(compact('appId', 'timestamp', 'nonceStr', 'signature'));
        return view();
    }

}