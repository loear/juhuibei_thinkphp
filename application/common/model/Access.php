<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/25
 * Time: 9:22
 */

namespace app\common\model;


class Access extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    public function node()
    {
        return $this->belongsTo('Node', 'node_id', 'id');
    }

}