<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 9:09
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url, config('wx.app_id'), config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    /**
     * 获取access_token | 建议用户规模小时每次直接去微信服务器取最新的token | 但微信access_token接口获取是有限制的 2000次/天
     *
     * @return mixed|null
     */
    public function get()
    {
        $token = $this->getFromCache();
        if (!$token) {
            return $this->getFromWxServer();
        } else {
            return $token;
        }
    }

    /**
     * 获取缓存中的token
     *
     * @return mixed|null
     */
    private function getFromCache(){
        $token = cache(self::TOKEN_CACHED_KEY);
        if(!$token){
            return $token;
        }
        return null;
    }

    /**
     * 获取微信服务器最新的token
     *
     * @return mixed
     * @throws Exception
     */
    private function getFromWxServer()
    {
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token, true);
        if (!$token)
        {
            throw new Exception('获取AccessToken异常');
        }
        if(!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
        $this->saveToCache($token);
        return $token['access_token'];
    }

    /**
     * 将token保存到缓存
     *
     * @param $token
     */
    private function saveToCache($token){
        cache(self::TOKEN_CACHED_KEY, $token, self::TOKEN_EXPIRE_IN);
    }
}