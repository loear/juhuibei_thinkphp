<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 11:16
 */

namespace app\api\controller\v1;
use app\common\model\Card as CardModel;
use app\common\model\Music as MusicModel;
use app\common\model\Config as ConfigModel;
use app\common\model\Theme as ThemeModel;
use app\common\model\Module as ModuleModel;
use app\common\model\Tag as TagModel;
use app\common\model\ThemeModule as ThemeModuleModel;
use app\common\model\ModuleTag as ModuleTagModel;
use app\common\model\Template as TemplateModel;

class Theme
{
    public function insertById($id)
    {
        $url = 'https://www.pica.im/card/api/card_info?theme_id=' . $id;
        $result_json = httpRequest($url);
        $data = json_decode($result_json, true)['data'];

        $music_model = new MusicModel();
        $music_model->url = $data['music'];
        $music_model->save();
        if ($music_model->id) {
            $config_model = new ConfigModel();
            $config_model->saveData($data['config']);
            if ($config_model->id) {
                $card_model = new CardModel();
                $card_model->music_id = $music_model->id;
                $card_model->config_id = $config_model->id;

                $card_model->bride_name = $data['bride_name'];
                $card_model->bridegroom_name = $data['bridegroom_name'];
                $card_model->cover = $data['cover'];
                $card_model->flip = $data['flip'];
                $card_model->is_locked = (int) $data['is_locked'];
                $card_model->theme_id = $data['theme_id'];
                $card_model->longitude = explode(',', $data['map_point'])[0];
                $card_model->latitude = explode(',', $data['map_point'])[1];
                $card_model->wedding_address = $data['wedding_address'];
                $card_model->wedding_time = $data['wedding_time'];

                $card_model->is_theme = $id;
                $card_model->save();
            }
        }

        $theme_model = new ThemeModel();
        $module_model = new ModuleModel();
        $tag_model = new TagModel();
        $tm_model = new ThemeModuleModel();
        $tpl_model = new TemplateModel();
        $mt_model = new ModuleTagModel();

        foreach ($data['module_data'] as $k=>$v)
        {
            $tpl_model->data([
                'name'  =>  $v['tpl_name'],
                'mark_name'  =>  $v['tpl_mark_name'],
                'html'  =>  $data['module_tpl'][$v['tpl_mark_name']]['html'],
                'css'  =>  $data['module_tpl'][$v['tpl_mark_name']]['css']
            ])->isUpdate(false)->save();
            $module_model->data([
                'name'=>$v['tpl_mark_name'],
                'tpl_id'=>$tpl_model->id
            ])->isUpdate(false)->save();
            $module_id = $module_model->id;
            /*$data_tm = [
                'theme_id'  =>  $id,
                'module_id' =>  $module_id,
                'index'     =>  $k,
                'deletable' =>  (int) $v['deletable']
            ];
            $theme_model->themeModule()->save($data_tm);*/
            /*$tm_model->theme_id = $id;
            $tm_model->module_id = $module_id;
            $tm_model->index = $k;
            $tm_model->deletable = (int) $v['deletable'];*/

            $tm_model->data([
                'theme_id'  =>  $id,
                'module_id' =>  $module_id,
                'index'     =>  $k,
                'deletable' =>  (int) $v['deletable']
            ])->isUpdate(false)->save();

            foreach ($v[$v['tpl_mark_name']] as $k2=>$v2) {
                $tag_model->data(['name'=>$k2])->isUpdate(false)->save();
                $mt_model->data([
                    'module_id' =>  $module_id,
                    'tag_id'    =>  $tag_model->id,
                    'index'     =>  $k2,
                    'value'     =>  $v2['value'],
                    'default_color' =>  $v2['default_color'],
                    'font_color'    =>  isset($v2['font_color']) ? $v2['font_color'] : '',
                    'attr_name'     =>  isset($v2['attr_name']) ? $v2['attr_name'] : '',
                ])->isUpdate(false)->save();
            }
        }
        // dump($data);

    }

    public function bat()
    {
        set_time_limit(0);
        for ( $i = 74; $i <= 97; ++$i) {
            if ($i == 37 || $i == 73) continue;
            $this->insertById($i);
            echo $i . '<br>';
        }
    }

}