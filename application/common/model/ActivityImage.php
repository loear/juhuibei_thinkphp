<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/18
 * Time: 21:48
 */

namespace app\common\model;


class ActivityImage extends BaseModel
{
    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

}