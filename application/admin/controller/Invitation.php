<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:28
 */

namespace app\admin\controller;
use app\common\model\Invitation as InvitationModel;


class Invitation extends BaseController
{
    public function index()
    {
        $invitation = InvitationModel::where('id','>','0')->paginate(10);

        $this->assign(['invitation'=>$invitation]);
        return $this->fetch();
    }

}