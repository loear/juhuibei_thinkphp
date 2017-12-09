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

    public function activity()
    {
        return $this->hasMany('Info', 'activity_id', 'id');
    }

}