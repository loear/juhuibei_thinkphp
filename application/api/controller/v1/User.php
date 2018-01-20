<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/25
 * Time: 23:29
 */

namespace app\api\controller\v1;

use app\api\service\WxCode;
use app\api\validate\IDMustBePostiveInt;
use app\common\model\User as UserModel;
use app\lib\exception\ActivityException;
use think\Request;

class User
{
    public function getUserInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $user_model = UserModel::find($id);
        if ($user_model) {
            return ['res'=>0, 'data'=>$user_model];
        }
        throw new ActivityException();
    }

    public function wxCode(Request $request)
    {
        $data = $request->get();
        if ($data) {
            $data['width'] = (int) $data['width'];
            $data['auto_color'] = (bool) $data['auto_color'];
            $weixin = new WxCode();
            $file_name = $weixin->makeCode($data);
            $file_name = 'https://www.juhuibei.com/' . $file_name;
            if ($file_name) {
                return ['res'=>0, 'data'=>$file_name];
            }
            throw new ActivityException();
        }
    }


}