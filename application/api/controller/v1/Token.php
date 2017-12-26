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
use app\api\validate\UserInfoSave;
use app\lib\exception\ActivityException;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
use app\common\model\User as UserModel;
use think\Request;

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


    public function saveUserInfo(Request $request)
    {
        (new UserInfoSave())->goCheck();
        $user_id    = $request->post('user_id');
        $nickname   = $request->post('nickname');
        $avatar_url = $request->post('avatar_url');
        $model = UserModel::find($user_id);
        if ($model->id) {
            $model->nickname    = $nickname;
            $model->avatar_url  = $avatar_url;
            $model->save();
            return ['res'=>0, 'data'=>$model->id];
        }
        throw new ActivityException();
    }
}