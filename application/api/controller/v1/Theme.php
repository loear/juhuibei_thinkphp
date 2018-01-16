<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 11:16
 */

namespace app\api\controller\v1;
use app\api\validate\IDMustBePostiveInt;
use app\common\model\Card as CardModel;
use app\common\model\Music as MusicModel;
use app\common\model\Config as ConfigModel;
use app\common\model\Theme as ThemeModel;
use app\common\model\Module as ModuleModel;
use app\common\model\Tag as TagModel;
use app\common\model\ThemeModule as ThemeModuleModel;
use app\common\model\ModuleTag as ModuleTagModel;
use app\common\model\Template as TemplateModel;
use app\lib\exception\ThemeMissException;
use think\Db;

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
    }

    public function bat()
    {
         set_time_limit(0);
        /*for ( $i = 74; $i <= 97; ++$i) {
            if ($i == 37 || $i == 73) continue;
            $this->insertById($i);
            echo $i . '<br>';
        }*/
        /*$theme_model = new ThemeModel();
        for ($i = 1; $i <= 97; ++$i) {
            $theme_model->data([
                'name' => '主题' . $i
            ])->isUpdate(false)->save();
        }*/

        /*for ($i = 1; $i <= 95; ++$i) {
            $card_model = CardModel::find($i);
            $module_data = Db::table('__THEME__')
                ->field('tm.module_id,tm.index,tm.deletable,m.name as tpl_mark_name,tpl.name as tpl_name,tpl.id as tpl_id')
                ->alias('t')
                ->join('__THEME_MODULE__ tm', 't.id = tm.theme_id')
                ->join('__MODULE__ m', 'tm.module_id = m.id')
                ->join('__TEMPLATE__ tpl', 'tpl.id = m.tpl_id')
                ->where('t.id', $card_model->theme_id)
                ->select()->toArray();

            foreach ($module_data as $k=>$v) {
                $result = Db::table('__MODULE_TAG__')
                    ->field('mt.value,mt.default_color,mt.font_color,mt.attr_name,mt.tag_id,t.name as tag_name')
                    ->alias('mt')
                    ->join('__TAG__ t', 't.id = mt.tag_id')
                    ->where('mt.module_id', $v['module_id'])
                    ->select();
                foreach ($result as $v2) {
                    $module_data[$k][$v['tpl_mark_name']][$v2['tag_name']] = $v2;
                }
            }
            $card_model->module_data = json_encode($module_data);
            $card_model->save();
            echo $i;
        }*/

        echo "success";

    }

    public function getThemeModule($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $theme_model = ThemeModel::with('themeModule.module')->find($id);
        if ($theme_model) {
            unset($theme_model->global_css);
            return ['res'=>0, 'data'=>$theme_model];
        }
        throw new ThemeMissException();
    }

}