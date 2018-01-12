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

        $tpl_model = new Template();
        $tpl_model->name = $data['tpl_name'];
        $tpl_model->mark_name = $data['tpl_mark_name'];
        $tpl_model->html = $tpl[$data['tpl_mark_name']]['html'];
        $tpl_model->css = $tpl[$data['tpl_mark_name']]['css'];
        $tpl_model->save();
        if ($tpl_model->id) {
            $this->name = $data['tpl_mark_name'];
            $this->tpl_id = $tpl_model->id;
            $this->save();
            return $this->id;
        }
    }

    private function muduleTpl()
    {
        return $this->hasOne('Template', 'id', 'tpl_id');
    }

}