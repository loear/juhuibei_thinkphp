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
    public function saveData($config)
    {
        $this->auto_play    = (int) $config['auto_play'];
        $this->speed_id     = 2;
        $this->un_tail      = (int) $config['un_tail'];
        $this->un_recommend = (int) $config['un_recommend'];
        $this->un_search    = (int) $config['un_search'];
        $this->save();
    }
}