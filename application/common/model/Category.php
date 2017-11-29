<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/22
 * Time: 7:55
 */

namespace app\common\model;
use app\common\lib\Data;


class Category extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'update_time',
        'create_time'
    ];
    protected $autoWriteTimestamp = true;

    public function img()
    {
        return $this->belongsTo('Image', 'head_image_id', 'id');
    }
}