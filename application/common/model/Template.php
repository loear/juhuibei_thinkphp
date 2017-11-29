<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 18:44
 */

namespace app\common\model;


use traits\model\SoftDelete;

class Template extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'update_time',
        'create_time'
    ];
    protected $autoWriteTimestamp = true;

    use SoftDelete;

    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

}