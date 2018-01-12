<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 18:10
 */

namespace app\common\model;


class Theme extends BaseModel
{
    public function themeModule()
    {
        return $this->hasMany('themeModule', 'theme_id', 'id');
    }

}