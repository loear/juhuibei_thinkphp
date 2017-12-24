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
use app\common\model\Info as InfoModel;
use app\common\model\Image as ImageModel;
use app\lib\encrypt\WXBizDataCrypt;
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
        $activity_model->latitude = $data['latitude'];
        $activity_model->longitude = $data['longitude'];
        $activity_model->is_only_group = $data['is_only_group'] ? '1' : '0';
        $activity_model->numbers = $data['numbers'];
        $activity_model->save();
        if ($activity_model->id) {
            $activity_model->info()->save([
                'user_id'       =>  $user_id,
                'activity_id'   =>  $activity_model->id,
                'is_master'     =>  1,  // 创建者身份
                'is_coming'     =>  1   // 创建者也是参与者
            ]);
            $activity_model->activityImage()->save([
                'image_id'      =>  $data['image_id'],
                'activity_id'   =>  $activity_model->id
            ]);
            return ['error'=>'0','data'=>'success'];
        }
        throw new ActivityException();
    }

    /**
     * 聚会活动列表
     *
     * @url /api/v1/activity/list/2
     * @param $id 用户的ID 包括发布者 & 参与者
     */
    public function getActivityList($user_id)
    {
        $activity_model = ActivityModel::hasWhere('info', ['user_id'=>$user_id])
            ->with(['info.user', 'activityImage.img'])
            ->select();
        $activity_list = $activity_model->toArray();
        $now_time = time();
        foreach ($activity_list as $k=>$v) {
            $start_time_diff = ($v['start_time'] - $now_time) > 0 ? ($v['start_time'] - $now_time) : 0;
            $end_time_diff = ($v['end_time'] - $now_time) > 0 ? ($v['end_time'] - $now_time) : 0 ;
            $activity_list[$k]['_start_time_diff'] = $start_time_diff;
            $activity_list[$k]['_end_time_diff'] = $end_time_diff;
            $activity_list[$k]['_numbers'] = InfoModel::where(['user_id'=>$user_id, 'activity_id'=>$v['id'], 'is_coming'=>1])->count();
        }
        return $activity_list;
    }

    /**
     * 获取聚会详细数据
     * @param $activity_id
     * @return array
     */
    public function getActivityInfo($id)
    {
        // 1. 通过活动ID和创建用户ID获取信息
        $result = ActivityModel::with(['info.user', 'activityImage.img'])->find($id);
        $result->_numbers = InfoModel::where(['activity_id'=>$id, 'is_coming'=>1])->count();
        $result->_start_time = date("Y-m-d H:i", $result->start_time);
        $now_time = time();
        $result->_countdown = ($result->start_time - $now_time) >  0 ? ($result->start_time - $now_time) : 0;
        return ['res'=>0, 'data'=>$result];
    }

    /**
     * 获取七牛上传TOKEN
     * @return array
     */
    public function getUploadToken()
    {
        require_once '../vendor/qiniu-php-sdk/autoload.php';
        $accessKey = config('qiniu.access_key');
        $secretKey = config('qiniu.secret_key');;
        $auth = new \Qiniu\Auth($accessKey, $secretKey);
        $bucket = config('qiniu.bucket_name');
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        return ['res'=>0, 'data'=>$token];
    }

    public function saveQiniuImage(Request $request)
    {
        $url = $request->post('url');
        if ($url) {
            $model = new ImageModel();
            $model->url     = $url;
            $model->type    = 2;
            $model->name    = '';
            $model->save();
            return ['res'=>0, 'data'=>$model->id];
        }
        return ['res'=>1, 'msg'=>'URL不能为空'];
    }

    /**
     * 保存 用户聚会关联信息
     *
     * @param Request $request
     * @return array
     */
    public function saveActivityUser(Request $request)
    {
        $user_id = $request->post('user_id');
        $activity_id = $request->post('activity_id');
        $resault = ActivityModel::hasWhere('info', ['user_id'=>$user_id, 'activity_id'=>$activity_id])->count();
        if (!$resault) {
            $activity_model = ActivityModel::find($activity_id);
            $activity_model->info()->save([
                'user_id'       =>  $user_id,
                'activity_id'   =>  $activity_model->id,
            ]);
            return ['error'=>'0','data'=>'success'];
        }
        return ['error'=>'0','data'=>'success'];
    }

    public function enCryptedData(Request $request)
    {
        $encryptedData = $request->post('encryptedData');
        $iv = $request->post('iv');
        $token = $request->post('token');
        $appid = config('wx.appid');
        $session_key = json_decode(cache($token), true)['session_key'];
        $pc = new WXBizDataCrypt($appid, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            return $data;
        } else {
            return $errCode;
        }
    }

}