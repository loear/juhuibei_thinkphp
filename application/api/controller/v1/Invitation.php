<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/23
 * Time: 6:41
 */

namespace app\api\controller\v1;
use app\common\model\Invitation as InvitationModel;
use app\common\model\User as UserModel;


use app\lib\exception\InvitationException;
use think\Db;
use think\Request;

class Invitation
{
    public function postInvitation()
    {
        $data = Request::instance()->post();
        $user = UserModel::getByOpenId($data['token']);
        unset($data['token']);
        if ($user) $data['user_id'] = $user->id;
        $data['wedding_date'] = strtotime($data['wedding_date'] . ' ' . $data['wedding_time']);
        unset($data['wedding_time']);

        if ($data) {
            $res = InvitationModel::create($data);
            if ($res) {
                return json(['error'=>'0','data'=>'success']);
            }
        }

        throw new InvitationException();

    }

}