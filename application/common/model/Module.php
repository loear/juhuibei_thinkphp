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
    public function saveData($data, $tpl)
    {


//        if ($tpl_model->id) {
//            $this->data(['name'=>$data['tpl_mark_name'],'tpl_id'=>$tpl_model->id])->isUpdate(false)->save();
//            return $this->id;
//        }
    }

    private function muduleTpl()
    {
        return $this->hasOne('Template', 'id', 'tpl_id');
    }

}