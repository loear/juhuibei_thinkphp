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
use app\common\model\Music as MusicModel;
use app\common\model\Theme as ThemeModel;
use app\lib\exception\CardMissException;
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

    /**
     * 获取卡信息
     *
     * @param $id
     * @return array
     */
    public function getCardById($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::find($id);
        $card_model->card_id = $card_model->id;
        $card_model->map_point = $card_model->longitude . ',' . $card_model->latitude;
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

    public function editCardInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::find($id);
        if (!$card_model) throw new CardMissException();
        $card_model->card_id = $card_model->id;
        $card_model->map_point = $card_model->longitude . ',' . $card_model->latitude;
        $music = Db::table('__MUSIC__')
            ->field('url')
            ->where('id', $card_model->music_id)
            ->find()
        ;
        $music_list = MusicModel::all();
        foreach ($music_list as $k=>$v) {
            if ($v['id'] == $card_model->music_id) {
                $v['checked'] = 1;
            }
        }
        $theme = Db::table('__THEME__')
            ->field('has_video')
            ->where('id', $card_model->theme_id)
            ->find()
        ;
        $module_data = json_decode($card_model->module_data, true);
        $tag = [];
        foreach ($module_data as $k=>$v) {
            foreach ($v[$v['tpl_mark_name']] as $k2=>$v2) {
                $prefix = substr($k2, 0, 3);
                $suffix = substr($k2, -1, 3);
                if ($prefix == 'pic' || $prefix == 'ima' || $suffix == '_bg') {
                    $v2['img'] = getImageInfo($v2['value']);
                    $v2['type'] = 'img';
                    $tag[] = $v2;
                } else if ($prefix == 'tex') {
                    $v2['type'] = 'txt';
                    $tag[] = $v2;
                }
            }
        }

        $data = date('Y-m-d', $card_model->wedding_time);
        $time = date('H:i', $card_model->wedding_time);
        $card_model->data = $data;
        $card_model->time = $time;
        $card_model->music = $music['url'];
        $card_model->music_list = $music_list;
        $card_model->tag = $tag;
        $card_model->cover = getImageInfo($card_model->cover);
        $card_model->wedding_video_cover = getImageInfo($card_model->wedding_video_cover);
        unset($card_model->module_data);
        $card_model->has_video = $theme['has_video'];
        return ['res'=>0, 'data'=>$card_model];
    }

}