<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/23
 * Time: 23:33
 */

namespace app\api\controller\v1;
use app\common\model\Info as InfoModel;
use app\common\model\ActivityImage as ActivityImageModel;

class Info
{
    /**
     * 获取用户聚会信息 是否本聚会的发起人，是否报名了该聚会，能上传的图片张数
     * @param $user_id
     * @param $activity_id
     */
    public function getUserActivityInfo($user_id, $activity_id)
    {
        $data = InfoModel::with(['userInfo'=>function($query){$query->withField('id,nickname,username,avatar_url,phone');}])
            ->where(['user_id' => $user_id, 'activity_id' => $activity_id])
            ->find();

        if ($data) {
            $data['_picture_number'] = ActivityImageModel::where(['user_id'=>$user_id, 'activity_id'=>$activity_id])->count();
            return ['res' => 0, 'data' => $data];
        }
        return ['res' => 1, 'msg' => '获取数据失败'];
    }

}