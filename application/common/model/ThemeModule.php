<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 14:53
 */

namespace app\common\model;


class ThemeModule extends BaseModel
{
    protected $hidden = ['id', 'create_time', 'delete_time', 'update_time'];

    public function module()
    {
        return $this->belongsTo('Module', 'module_id', 'id');
    }

    public function theme()
    {
        return $this->belongsTo('Theme', 'theme_id', 'id');
    }
}