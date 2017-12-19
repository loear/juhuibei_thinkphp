<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/10
 * Time: 0:34
 */

namespace app\common\model;


class Info extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,avatar_url,phone');;
    }
}