<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/23
 * Time: 23:33
 */

namespace app\api\controller\v1;
use app\api\validate\ActivityUserSave;
use app\common\model\Info as InfoModel;
use app\common\model\ThemeUser as ThemeUserModel;
use app\common\model\ActivityImage as ActivityImageModel;
use app\lib\exception\InfoMissException;

class Info
{
    /**
     * 获取用户聚会信息 是否本聚会的发起人，是否报名了该聚会，能上传的图片张数
     * @param $user_id
     * @param $activity_id
     */
    public function getUserActivityInfo($user_id, $activity_id)
    {
        (new ActivityUserSave())->goCheck();
        $info_model = InfoModel::with(['userInfo'=>function($query){$query->withField('id,nickname,username,avatar_url,phone');}])
            ->where(['user_id' => $user_id, 'activity_id' => $activity_id])
            ->find();

        if ($info_model) {
            $info_model->_picture_number = ActivityImageModel::where(['user_id'=>$user_id, 'activity_id'=>$activity_id])->count();
            $info_model->_is_uploading  = ($info_model->picture_number - $info_model->_picture_number > 0) ? true : false;
            return ['res' => 0, 'data' => $info_model];
        }
        throw new InfoMissException();
    }

    public function test()
    {
        $url = 'https://www.pica.im/card/api/card_info?theme_id=' . 96;
        $result_json = httpRequest($url);
        $result = json_decode($result_json, true);

        $theme_user_model = ThemeUserModel::where(['user_id'=>1, 'theme_id'=>1])->find();
        $result = json_decode($theme_user_model->module_data, true);
        return $result;
    }

}