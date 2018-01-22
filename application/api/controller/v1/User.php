<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/25
 * Time: 23:29
 */

namespace app\api\controller\v1;

use app\api\service\Token;
use app\api\service\WxCode;
use app\api\validate\IDMustBePostiveInt;
use app\common\model\User as UserModel;
use app\common\model\Card as CardModel;
use app\lib\exception\ActivityException;
use app\lib\exception\UserException;
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

    /**
     * 获取用户的会员信息 | 如果是VIP1且请柬数量为1返回card_id theme_id
     *
     * @return array
     * @throws UserException
     */
    public function getVipInfo()
    {
        $uid = Token::getCurrentUid();
        $user_model = UserModel::field('id,vip')->find($uid);
        if ($user_model) {
            $card_id = $theme_id = 0;
            $card_count = CardModel::where(['user_id'=>$uid])->count();
            if ($user_model->vip == 1 && $card_count == 1) {
                $card_model = CardModel::where(['user_id'=>$uid])->find();
                $card_id  = $card_model->id;
                $theme_id = $card_model->theme_id;
            }
            $user_model->card_count = (int) $card_count;
            $user_model->card_id    = (int) $card_id;
            $user_model->theme_id   = (int) $theme_id;
            return ['res'=>0, 'data'=>$user_model];
        }
        throw new UserException();
    }


}