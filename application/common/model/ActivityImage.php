<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/18
 * Time: 21:48
 */

namespace app\common\model;


use traits\model\SoftDelete;

class ActivityImage extends BaseModel
{
    use SoftDelete;

    protected $hidden = ['delete_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function userInfo()
    {
        return $this->hasOne('User', 'id', 'user_id');
    }

    public function imgInfo()
    {
        return $this->hasOne('Image', 'id', 'image_id');
    }

}