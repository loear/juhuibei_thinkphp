<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:07
 */

namespace app\api\controller\v1;

use app\common\model\Activity as ActivityModel;
use app\common\model\User as UserModel;
use app\lib\exception\ActivityException;
use think\Request;

class Activity
{
    /**
     * 聚会活动提交
     *
     * @url /api/v1/activity/submit
     * @return \think\response\Json
     * @throws ActivityException
     */
    public function postActivity()
    {
        $data = Request::instance()->post();
        $user = UserModel::getByOpenId($data['token']);
        unset($data['token']);
        if ($user) $data['user_id'] = $user->id;
        $data['start_time'] = strtotime($data['start_data'] . ' ' . $data['start_time']);
        $data['end_time'] = strtotime($data['end_data'] . ' ' . $data['end_time']);
        unset($data['end_data']);
        unset($data['token']);
        unset($data['wedding_time']);
        $data['is_only_group'] = $data['is_only_group'] ? '1' : '0';

        if ($data) {
            $res = ActivityModel::create($data);
            if ($res) {
                return json(['error'=>'0','data'=>'success']);
            }
        }

        throw new ActivityException();

    }

}