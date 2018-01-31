<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/16
 * Time: 17:26
 */

namespace app\common\model;


class Bless extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id');
    }

}