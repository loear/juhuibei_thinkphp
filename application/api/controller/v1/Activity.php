<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:07
 */

namespace app\api\controller\v1;

use app\api\validate\ActivityPostSubmit;
use app\common\model\Activity as ActivityModel;
use app\common\model\User as UserModel;
use app\common\model\User;
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
        (new ActivityPostSubmit())->goCheck();
        $user_id = $data['user_id'];
        $activity_model = new ActivityModel();
        $activity_model->title = $data['title'];
        $activity_model->description = $data['description'];
        $activity_model->start_time = strtotime($data['start_date'] . ' ' . $data['start_time']);
        $activity_model->end_time = strtotime($data['end_date'] . ' ' . $data['end_time']);
        $activity_model->gourmet_title = $data['gourmet_title'];
        $activity_model->gourmet_address = $data['gourmet_address'];
        $activity_model->geopoint = $data['geopoint'];
        $activity_model->is_only_group = $data['is_only_group'] ? '1' : '0';
        $activity_model->numbers = $data['numbers'];
        $activity_model->save();
        if ($activity_model->id) {
            $activity_model->activity()->save([
                'user_id'       =>  $user_id,
                'activity_id'   =>  $activity_model->id
            ]);
            return ['error'=>'0','data'=>'success'];
        }

        throw new ActivityException();

    }

}