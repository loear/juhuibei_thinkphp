<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/3/6
 * Time: 9:18
 */

namespace app\home\service;


use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Cache;

class Ticket
{
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxAccessTokenUrl;

    public function __construct()
    {
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxAccessTokenUrl = sprintf(config('wx.access_token_url'), $this->wxAppId, $this->wxAppSecret);
    }

    private function getAccessTokenError($wxResult)
    {
        throw new WeChatException([
            'msg'       =>  $wxResult['errmsg'],
            'errorCode' =>  $wxResult['errcode']
        ]);

    }

    private function saveToCache($cachedValue)
    {
        $key        = md5(config('setting.access_token_key') . config('secure.token_salt'));
        $value      = json_encode($cachedValue);
        $expire_in  = config('setting.token_expire_in');
        $request    = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg'       => '服务器缓存异常',
                'errorCode' =>  10005
            ]);
        }
        return true;
    }

    private function saveTicketToCache($cachedValue)
    {
        $key        = md5(config('setting.jaspi_ticket_key') . config('secure.token_salt'));
        $value      = json_encode($cachedValue);
        $expire_in  = config('setting.ticket_expire_in');
        $request    = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg'       => '服务器缓存异常',
                'errorCode' =>  10005
            ]);
        }
        return true;
    }

    public function getAccessToken()
    {
        $key = md5(config('setting.access_token_key') . config('secure.token_salt'));
        $cacheValue = Cache::get($key);
        if (!$cacheValue) {
            $result = curl_get($this->wxAccessTokenUrl);
            $wxResult = json_decode($result, true);
            if (empty($wxResult)) {
                throw new Exception('获取access_token异常，微信内部错误');
            } else {
                $resFail = array_key_exists('errcode', $wxResult);
                if ($resFail) {
                    $this->getAccessTokenError($wxResult);
                } else {
                    $this->saveToCache($wxResult);
                    return $wxResult['access_token'];
                }
            }
        } else {
            $wxResult = json_decode($cacheValue, true);
            return $wxResult['access_token'];
        }
    }

    public function getTicket()
    {
        $accesstoken = $this->getAccessToken();
        $url = sprintf(config('wx.jaspi_ticket_url'), $accesstoken);
        $key = md5(config('setting.jaspi_ticket_key') . config('secure.token_salt'));
        $cacheValue = Cache::get($key);
        if (!$cacheValue) {
            $result = curl_get($url);
            $wxResult = json_decode($result, true);
            if (empty($wxResult)) {
                throw new Exception('获取jsapi_ticket异常，微信内部错误');
            } else {
                // $resFail = array_key_exists('errcode', $wxResult);
                if ($wxResult['errcode'] > 0) {
                    $this->getAccessTokenError($wxResult);
                } else {
                    $this->saveTicketToCache($wxResult);
                    return $wxResult['ticket'];
                }
            }
        } else {
            $wxResult = json_decode($cacheValue, true);
            return $wxResult['ticket'];
        }
    }
}