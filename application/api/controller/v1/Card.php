<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 10:54
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePostiveInt;
use app\common\model\Card as CardModel;
use app\common\model\Theme as ThemeModel;
use think\Db;

class Card
{
    public function saveCard()
    {

    }

    public function getCardByThemeId($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::find($id);
        $card_model->card_id = $card_model->id;
        $card_model->map_point = $card_model->longitude . $card_model->latitude;
        $music = Db::table('__MUSIC__')
            ->field('url')
            ->where('id', $card_model->music_id)
            ->find();
        $config = Db::table('__CONFIG__')
            ->field('c.un_tail,c.un_recommend,c.un_search,s.name as speed_name,s.speed')
            ->alias('c')
            ->join('__SPEED__ s', 's.id = c.speed_id')
            ->where('c.id', $card_model->config_id)
            ->select();
        $module_data = Db::table('__THEME__')
            ->field('tm.module_id,tm.index,tm.deletable,m.name as tpl_mark_name,tpl.name as tpl_name,tpl.id as tpl_id')
            ->alias('t')
            ->join('__THEME_MODULE__ tm', 't.id = tm.theme_id')
            ->join('__MODULE__ m', 'tm.module_id = m.id')
            ->join('__TEMPLATE__ tpl', 'tpl.id = m.tpl_id')
            ->where('t.id', $card_model->theme_id)
            ->select()->toArray();

        $module_tpl = [];
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
           $module_tpl[$v['tpl_mark_name']] = Db::table('__TEMPLATE__')
                ->field('html,css')
                ->where('id', $v['module_id'])
                ->find();
        }
        $card_model->music = $music['url'];
        $card_model->config = $config;
        $card_model->module_data = $module_data;
        $card_model->module_tpl = $module_tpl;
        if ($card_model) {
            return ['success'=>1, 'data'=>$card_model];
        }
        return ['field'=>1];
    }

    public function getCardById($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::find($id);
        $card_model->card_id = $card_model->id;
        $card_model->map_point = $card_model->longitude . $card_model->latitude;
        $music = Db::table('__MUSIC__')
            ->field('url')
            ->where('id', $card_model->music_id)
            ->find();
        $config = Db::table('__CONFIG__')
            ->field('c.un_tail,c.un_recommend,c.un_search,s.name as speed_name,s.speed')
            ->alias('c')
            ->join('__SPEED__ s', 's.id = c.speed_id')
            ->where('c.id', $card_model->config_id)
            ->select();
        $module_data = json_decode($card_model->module_data, true);
        $module_tpl = [];
        foreach ($module_data as $k=>$v) {
            $module_tpl[$v['tpl_mark_name']] = Db::table('__TEMPLATE__')
                ->field('html,css')
                ->where('id', $v['module_id'])
                ->find();
        }
        $card_model->music = $music['url'];
        $card_model->config = $config;
        $card_model->module_data = $module_data;
        $card_model->module_tpl = $module_tpl;
        if ($card_model) {
            return ['success'=>1, 'data'=>$card_model];
        }
        return ['field'=>1];
    }

}