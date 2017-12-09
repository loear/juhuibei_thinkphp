<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/7/16
 * Time: 23:13
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
use app\common\model\User as UserModel;

class Token
{
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $result = $ut->get();
        return $result;
    }

    public function verifyToken($token='')
    {
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }


    public function saveUserInfo($data)
    {
        if (!empty($data) && $data['user_id'] > 0) {
            $model = UserModel::find($data['user_id']);
            $model->nickname = $data['nickName'];
            $model->avatar_url = $data['avatarUrl'];
            $model->save();
            return ['status'=>'success'];
        }
        throw new ParameterException([
           '参数错误'
        ]);
    }
}