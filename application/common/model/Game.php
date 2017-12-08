<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:35
 */

namespace app\common\model;


class Game extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

}