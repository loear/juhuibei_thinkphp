<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:16
 */

namespace app\common\model;


class Activity extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function info()
    {
        return $this->hasMany('Info', 'activity_id', 'id');
    }

    public function activityImage()
    {
        return $this->hasMany('ActivityImage', 'activity_id', 'id');
    }

}