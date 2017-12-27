<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/27
 * Time: 13:44
 */

namespace app\api\Service;


use app\lib\exception\WeChatException;
use think\Cache;
use think\Exception;

class Template
{
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxTemplateUrl;

    public function __construct()
    {
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxTemplateUrl = sprintf(config('wx.access_token_url'), $this->wxAppId, $this->wxAppSecret);
    }

    public function getAccessToken()
    {
        $key = md5(config('setting.access_token_key') . config('secure.token_salt'));
        $cacheValjue = Cache::get($key);
        if (!$cacheValjue) {
            $result = curl_get($this->wxTemplateUrl);
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
            $wxResult = json_decode($cacheValjue, true);
            return $wxResult['access_token'];
        }
    }

    public function sendTemplateMsg($access_token, $template_id, $page, $openid, $form_id, $data)
    {
        $url = sprintf(config('wx.template_url'), $access_token);
        $params = [
            'touser'            =>  $openid,
            'template_id'       =>  $template_id,
            'page'              =>  $page,
            'form_id'           =>  $form_id,
            'data'              =>  $data,
        ];
        $curl_result = httpRequest($url, 'POST', json_encode($params), ["content-type: application/json"]);
        if (!$curl_result) throw new Exception('发送失败，微信内部错误');
        $result = json_decode($curl_result, true);
        if ($result['errcode'] == 0) {
            return true;
        } else {
            $this->getAccessTokenError($result);
        }

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

}