<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 16:31
 */

namespace app\common\model;


class Tag extends BaseModel
{
    public function saveData($name)
    {
        $this->data(['name'=>$name])->isUpdate(false)->save();
        return $this->id;
    }

    public function moduleTag()
    {
        return $this->hasMany('ModuleTag', 'module_id', 'id');
    }


}