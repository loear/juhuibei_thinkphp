<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 14:01
 */

namespace app\common\model;


class Config extends BaseModel
{
    protected $hidden = ['id', 'create_time', 'delete_time', 'update_time'];

    public function saveData($config)
    {
        $this->auto_play    = (int) $config['auto_play'];
        $this->speed_id     = 2;
        $this->un_tail      = (int) $config['un_tail'];
        $this->un_recommend = (int) $config['un_recommend'];
        $this->un_search    = (int) $config['un_search'];
        $this->save();
    }

    public function speed()
    {
        return $this->hasOne('Speed', 'id', 'speed_id');
    }
}