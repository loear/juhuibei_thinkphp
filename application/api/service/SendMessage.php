<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 9:20
 */

namespace app\api\service;


use app\common\model\User;
use app\lib\exception\CreateMessageException;
use app\lib\exception\JoinMessageException;
use app\lib\exception\UserException;

class SendMessage extends WxMessage
{
    const CREATE_MSG_ID = 'aOADRWuUFRmJInF1MCeazS-VV4jjKpmYiwcbwHD6zKs';  // 创建活动 模板消息ID号
    const JOIN_MSG_ID   = 'DmfZvJhw0emVc4Q9GKg7RAP3Kd_oAfJmEfUaKvtaLnA';  // 加入活动 模板消息ID号
    public function sendCreateMessage($message)
    {
        if (!$message) {
            throw new CreateMessageException();
        }
        $this->tplID    = self::CREATE_MSG_ID;
        $this->formID   = $message['form_id'];
        $this->page     = 'pages/activity-detail/index?user_id=' . $message['user_id'] . '&activity_id=' . $message['activity_id'];
        $this->prepareCreateMessageData($message);
        // $this->emphasisKeyWord='keyword2.DATA';
        return parent::sendMessage($this->getUserOpenID($message['user_id']));
    }

    private function prepareCreateMessageData($message)
    {
        $data = [
                'keyword1'  =>  [   // 活动名称
                'value' =>  $message['title'],
                'color' => '#FF4444'
            ],
                'keyword2'  =>  [   // 活动时间
                'value' =>  date('Y年m月d日 H:i', $message['start_time'])
            ],
                'keyword3'  =>  [   // 活动地址
                'value' =>  $message['address']
            ],
        ];
        $this->data = $data;
    }

    public function sendJoinMessage($message)
    {
        if (!$message) {
            throw new JoinMessageException();
        }
        $this->tplID    = self::JOIN_MSG_ID;
        $this->formID   = $message['form_id'];
        $this->page     = 'pages/activity-detail/index?user_id=' . $message['user_id'] . '&activity_id=' . $message['activity_id'];
        $this->prepareJoinMessageData($message);
        // $this->emphasisKeyWord='keyword2.DATA';
        return parent::sendMessage($this->getUserOpenID($message['user_id']));
    }

    private function prepareJoinMessageData($message)
    {
        $data = [
                'keyword1'  =>  [   // 活动名称
                'value' =>  $message['title'],
                'color' => '#FF4444'
            ],
                'keyword2'  =>  [   // 活动时间
                'value' =>  $message['update_time']
            ],
                'keyword3'  =>  [   // 报名人数
                'value' =>  $message['number']
            ],
                'keyword4'  =>  [   // 活动地点
                'value' =>  $message['gourmet_title']
            ],
                'keyword5'  =>  [   // 活动时间
                'value' =>  date('Y年m月d日 H:i', $message['start_time'])
            ],
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}