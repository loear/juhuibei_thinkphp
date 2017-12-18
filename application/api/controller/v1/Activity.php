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
                'activity_id'   =>  $activity_model->id,
                'is_master'     =>  1,  // 创建者身份
                'is_coming'     =>  1   // 创建者也是参与者
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
        $activity_model = ActivityModel::hasWhere('activity', ['user_id'=>$user_id])
            ->with('activity.user')
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

    public function getActivityInfo($user_id, $activity_id)
    {
        $author = [];
        $story_author = [];
        $author_list = [];
        $share_list = [];
        $data = [
           'id'             =>  '2460',
            'title'         =>  '我也很想他',
            'cover'         =>  'http://image.wufazhuce.com/Fg9M64Cogg9OM12vVO1CaHQnXONB',
            'isfirst'       =>  '0',
            'story_title'   =>  '喜欢就得往前凑，这才是生活的真勇敢',
            'story'         =>  '\n<p>为了让我们的好朋友好妹妹蒋晗，走出失恋阴霾，重见恋爱天日，上个礼拜，我们几个许久未见的发小难得地聚在一起吃了顿饭。</p>',
            'lyric'         =>  '那时我们总有好多话\r\n什么事都可以讲',
            'info'          =>  '所属专辑：Stefanie\r\n演唱者：孙燕姿',
            'platform'      =>  '1',
            'music_id'      =>  '376368',
            'charge_edt'    =>  '（责任编辑：山山  sunshen@wufazhuce.com）',
            'related_to'    =>  '0',
            'web_url'       =>  'http://h.xiami.com/one-share.html?id=376368',
            'praisenum'     =>  '1976',
            'hide_flag'     =>  '0',
            'sort'          =>  '0',
            'maketime'      =>  '2017-12-08 06:00:00',
            'last_update_date'  =>  '2017-12-07 18:19:19',
            'read_num'      =>  '58802',
            'story_summary' =>  '你喜欢他，他喜欢她，她喜欢我，我喜欢你，谁也没错',
            'audio'         =>  '',
            'anchor'        =>  '',
            'editor_email'  =>  'sunshen@wufazhuce.com',
            'related_musics'=>  '',
            'album'         =>  'Stefanie',
            'start_video'   =>  '',
            'media_type'    =>  '1',
            'copyright'     =>  '',
            'audio_duration'=>  '0',
            'author'        =>  $author,
            'story_author'  =>  $story_author,
            'author_list'   =>  $author_list,
            'feeds_cover'   =>  'http://image.wufazhuce.com/FuKDd9NZOoR43n5m1O6_Mp3XC3sH?imageMogr2/auto-orient/blur/50x50/quality/75|imageslim',
            'next_id'       =>  '2487',
            'previous_id'   =>  '2486',
            'tag_list'      =>  [],
            'share_list'    =>  $share_list,
            'sharenum'      =>  637,
            'commentnum'    =>  456
        ];
        $result = [
            'res'   =>  0,
            'data'  =>  $data
        ];

        // 1. 通过活动ID和创建用户ID获取信息
        $result = ActivityModel::hasWhere('activity', ['user_id'=>$user_id, 'activity_id'=>$activity_id, 'is_master'=>1])
            ->with('activity.user')
            ->find();

        return $result;
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

}