<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 11:46
 */

namespace app\common\model;


class Card extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function theme()
    {
        return $this->hasOne('Theme', 'id', 'theme_id');
    }

    public function config()
    {
        return $this->hasOne('Config', 'id', 'config_id');
    }

    public function music()
    {
        return $this->hasOne('Music', 'id', 'music_id');
    }
}