<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:07
 */

namespace app\api\controller\v1;

use app\api\validate\ActivitySave;
use app\api\validate\ActivityImageSave;
use app\api\validate\ActivityUserSave;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\UserComingSave;
use app\common\model\Activity as ActivityModel;
use app\common\model\Info as InfoModel;
use app\common\model\User as UserModel;
use app\lib\encrypt\WXBizDataCrypt;
use app\lib\exception\ActivityException;
use app\lib\exception\ActivityMissException;
use think\Request;

class Activity
{
    /**
     * 聚会活动提交
     *
     * @url /api/v1/activity_submit
     * @return \think\response\Json
     * @throws ActivityException
     */
    public function saveActivity(Request $request)
    {
        (new ActivitySave())->goCheck();
        $data = $request->post();
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
        // $activity_model->is_only_group = $data['is_only_group'] ? '1' : '0';
        $activity_model->numbers = $data['numbers'];
        $activity_model->save();
        if ($activity_model->id) {
            $activity_model->info()->save([
                'user_id'       =>  $user_id,
                'activity_id'   =>  $activity_model->id,
                'is_master'     =>  1,  // 创建者身份
                'is_coming'     =>  1,  // 创建者也是参与者
                'picture_number'=>  2   // 创建者可以上传两张照片
            ]);
            $activity_model->activityImage()->save([
                'image_id'      =>  $data['image_id'],
                'activity_id'   =>  $activity_model->id,
                'name'          =>  '聚会封面',
                'description'   =>  '这张照片让我想起了...'
            ]);
            $user_model = UserModel::find($user_id);
            $user_model->username = $data['username'];
            $user_model->phone    = $data['phone'];
            $user_model->save();
            if ($user_model->id) {
                return ['res'=>0, 'data'=>'保存成功'];
            }
        }
        throw new ActivityException();
    }

    /**
     * 获取聚会活动列表
     *
     * @url /api/v1/activity_list/2
     * @param $id
     * @return array
     * @throws ActivityMissException
     */
    public function getActivityList($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $activity_model = ActivityModel::hasWhere('info', ['user_id'=>$id])
            ->with(['info.user', 'activityImage.img'])
            ->select();
        $activity_list = $activity_model->toArray();
        if (!$activity_list) throw new ActivityMissException();
        $now_time = time();
        foreach ($activity_list as $k=>$v) {
            $start_time_diff    = ($v['start_time'] - $now_time) > 0 ? ($v['start_time'] - $now_time) : 0;
            $end_time_diff      = ($v['end_time'] - $now_time) > 0 ? ($v['end_time'] - $now_time) : 0 ;
            $activity_list[$k]['_start_time_diff'] = $start_time_diff;
            $activity_list[$k]['_end_time_diff']   = $end_time_diff;
            $activity_list[$k]['_numbers']         = InfoModel::where(['user_id'=>$id, 'activity_id'=>$v['id'], 'is_coming'=>1])->count();
        }
        return ['res'=>0, 'data'=>$activity_list];
    }

    /**
     * 获取聚会详细数据
     *
     * @url /api/v1/activity_info/2
     * @param $id
     * @return array
     * @throws ActivityMissException
     */
    public function getActivityInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        // 1. 通过活动ID和创建用户ID获取信息
        $activity_model = ActivityModel::with(['info.user', 'activityImage.img'])->find($id);
        if ($activity_model) {
            $now_time = time();
            $activity_model->_numbers    = InfoModel::where(['activity_id' => $id, 'is_coming' => 1])->count();
            $activity_model->_start_time = date("Y-m-d H:i", $activity_model->start_time);
            $activity_model->_countdown  = ($activity_model->start_time - $now_time) > 0 ? ($activity_model->start_time - $now_time) : 0;
            return ['res' => 0, 'data' => $activity_model];
        }
        throw new ActivityMissException();
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

    /**
     * 保存聚会照片
     *
     * @param Request $request
     * @return array
     * @throws ActivityException
     */
    public function saveActivityImage(Request $request)
    {
        (new ActivityImageSave())->goCheck();
        $image_id       = $request->post('image_id');
        $user_id        = $request->post('user_id');
        $activity_id    = $request->post('activity_id');
        $activity_model = ActivityModel::find($activity_id);
        if ($activity_model->id) {
            $activity_model->activityImage()->save([
                'image_id'      =>  $image_id,
                'activity_id'   =>  $activity_model->id,
                'user_id'       =>  $user_id,
                'name'          =>  '聚会照片',
                'description'   =>  '这张照片让我想起了...'
            ]);
            $result = ActivityModel::with(['info.user', 'activityImage.img'])->find($activity_id);
            $result->_numbers = InfoModel::where(['activity_id'=>$activity_id, 'is_coming'=>1])->count();
            $result->_start_time = date("Y-m-d H:i", $result->start_time);
            $now_time = time();
            $result->_countdown = ($result->start_time - $now_time) >  0 ? ($result->start_time - $now_time) : 0;
            return ['res'=>0, 'data'=>$result];
        }
        throw new ActivityException();
    }

    /**
     * 保存 用户聚会关联信息 用户打开聚会页面就做关联
     *
     * @param Request $request
     * @return array
     */
    public function saveActivityUser(Request $request)
    {
        (new ActivityUserSave())->goCheck();
        $user_id        = $request->post('user_id');
        $activity_id    = $request->post('activity_id');
        $resault = ActivityModel::hasWhere('info', ['user_id'=>$user_id, 'activity_id'=>$activity_id])->count();
        if (!$resault) {  // 未关联
            $activity_model = ActivityModel::find($activity_id);
            $activity_model->info()->save([
                'user_id'       =>  $user_id,
                'activity_id'   =>  $activity_model->id,
            ]);
            return ['error'=>'0','data'=>'success'];
        }
        return ['error'=>'0','data'=>'success'];
    }

    /**
     * 保存用户报名信息
     *
     * @param Request $request
     * @return array
     * @throws ActivityException
     */
    public function saveUserComing(Request $request)
    {
        (new UserComingSave())->goCheck();
        $user_id     = $request->post('user_id');
        $activity_id = $request->post('activity_id');
        $username    = $request->post('username');
        $phone       = $request->post('phone');
        $user_model = UserModel::find($user_id);
        if ($user_model->id) { // 先更新用户信息
            $user_model->username = $username;
            $user_model->phone    = $phone;
            $user_model->save();
            $info_model = InfoModel::where(['user_id'=>$user_id, 'activity_id'=>$activity_id])->find();
            if ($info_model->id) { // 再保存报名信息
                $info_model->is_coming = 1;
                $info_model->save();
                return ['res'=>0, 'data'=>'保存成功'];
            }
        }
        throw new ActivityException();
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