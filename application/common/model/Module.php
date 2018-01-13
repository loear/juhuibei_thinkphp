<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 15:02
 */

namespace app\common\model;


class Module extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    public function saveData($data, $tpl)
    {


//        if ($tpl_model->id) {
//            $this->data(['name'=>$data['tpl_mark_name'],'tpl_id'=>$tpl_model->id])->isUpdate(false)->save();
//            return $this->id;
//        }
    }

    public function template()
    {
        return $this->hasOne('Template', 'id', 'tpl_id');
    }

    public function moduleTag()
    {
        return $this->hasMany('moduleTag', 'module_id', 'id');
    }

}