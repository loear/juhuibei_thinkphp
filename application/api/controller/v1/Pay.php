<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/2
 * Time: 17:54
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;


class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder($id='')
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $pay= new PayService($id);
        return $pay->pay();
    }

    public function redirectNotify()
    {
        $notify = new WxNotify();
        $notify->handle();
    }

    public function notifyConcurrency()
    {
        $notify = new WxNotify();
        $notify->handle();
    }

    public function receiveNotify()
    {
//        $xmlData = file_get_contents('php://input');
//        Log::error($xmlData);
        $notify = new WxNotify();
        $notify->handle();
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http:/zerg.cn/api/v1/pay/re_notify?XDEBUG_SESSION_START=13133',
//            $xmlData);
//        return $result;
//        Log::error($xmlData);
    }
}