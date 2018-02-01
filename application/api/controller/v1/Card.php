<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/12
 * Time: 10:54
 */

namespace app\api\controller\v1;

use app\api\service\Token;
use app\api\validate\CardCoverMake;
use app\api\validate\CardCreate;
use app\api\validate\CardSave;
use app\api\validate\IDMustBePostiveInt;
use app\common\model\Card as CardModel;
use app\common\model\User as UserModel;
use app\common\model\Music as MusicModel;
use app\common\model\Theme as ThemeModel;
use app\lib\exception\CardMissException;
use app\lib\exception\CreateCardException;
use think\Db;
use think\Exception;
use think\Request;

class Card
{
    public function saveCard(Request $request)
    {
        (new CardSave())->goCheck();
        $data = $request->post();
        $card_model = CardModel::find($data['card_id']);
        if ($card_model) {
            $has_video = $data['has_video'];
            $has_video_ori = $data['has_video_ori'];
            $wedding_time = strtotime($data['date'] . ' ' . $data['time']);
            $map_point = $data['longitude'] . ',' . $data['latitude'];
            $card_model->bride_name         = $data['bride_name'];
            $card_model->bride_phone        = $data['bride_phone'];
            $card_model->bridegroom_name    = $data['bridegroom_name'];
            $card_model->bridegroom_phone   = $data['bridegroom_phone'];
            $card_model->cover              = $data['cover'];
            $card_model->longitude          = $data['longitude'];
            $card_model->latitude           = $data['latitude'];
            $card_model->wedding_address    = $data['wedding_address'];
            $card_model->wedding_time       = $wedding_time;
            $card_model->video_switch       = (int)$has_video;
            if ($has_video) {
                $card_model->wedding_video = $data['wedding_video'];
                $card_model->wedding_video_cover = $data['wedding_video_cover'];
            }
            $module_data = json_decode($card_model->module_data, true);
            $module_data_new = [];
            foreach ($module_data as $k=>$v) {
                if ($has_video_ori && !$has_video) {
                    if ($v['tpl_mark_name'] == 'video') {
                        unset($module_data[$k]);
                        continue;
                    }
                }
                foreach ($v[$v['tpl_mark_name']] as $k2=>$v2) {
                    $prefix = substr($k2, 0, 3);
                    $suffix = substr($k2, -1, 3);
                    if ($k2 == 'wedding_time') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $wedding_time;
                    } else if ($k2 == 'bride_name') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['bride_name'];
                    } else if ($k2 == 'wedding_address') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['wedding_address'];
                    } else if ($k2 == 'bridegroom_name') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['bridegroom_name'];
                    } else if ($k2 == 'wedding_video') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['wedding_video'];
                    } else if ($k2 == 'wedding_video_cover') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['wedding_video_cover'];
                    } else if ($k2 == 'bridegroom_phone') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['bridegroom_phone'];
                    } else if ($k2 == 'bride_phone') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $data['bride_phone'];
                    } else if ($k2 == 'map_point') {
                        $module_data[$k][$v['tpl_mark_name']][$k2]['value'] = $map_point;
                    }else if ($prefix == 'pic' || $prefix == 'ima' || $suffix == '_bg') {
                        if ($data['tag_change']) {
                            foreach ($data['tag'] as $k3=>$v3) {
                                unset($v3['img']);
                                if ($v2['tag_id'] == $v3['tag_id']) {
                                    $module_data[$k][$v['tpl_mark_name']][$k2] = $v3;
                                }
                            }
                        }
                    }
                }
                $module_data_new[] = $module_data[$k];
            }
            $card_model->module_data = json_encode($module_data_new);
            if ($data['music_id']) {
                $card_model->music_id = $data['music_id'];
            }
            $card_model->isUpdate(true)->save();
            return ['res'=>0];
        }
        throw new CardMissException();

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

    /**
     * 获取编辑的卡信息
     *
     * @param $id
     * @return array
     * @throws CardMissException
     */
    public function editCardInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::field('
            bride_name,
            bride_phone,
            bridegroom_name,
            bridegroom_phone,
            wedding_time,
            latitude,
            longitude,
            wedding_address,
            cover,
            wedding_video,
            wedding_video_cover,
            music_id,
            theme_id,
            module_data,
            video_switch
        ')
            ->find($id)
        ;
        if (!$card_model) throw new CardMissException();
        $music_list = MusicModel::all();
        foreach ($music_list as $k=>$v) {
            if ($v['id'] == $card_model->music_id) {
                $v['checked'] = true;
            }
        }
        $theme = ThemeModel::field('has_video')->where(['id'=>$card_model->theme_id])->find();
        $module_data = json_decode($card_model->module_data, true);
        $tag = [];
        foreach ($module_data as $k=>$v) {
            foreach ($v[$v['tpl_mark_name']] as $k2=>$v2) {
                $prefix = substr($k2, 0, 3);
                $suffix = substr($k2, -1, 3);
                if ($prefix == 'pic' || $prefix == 'ima' || $suffix == '_bg') {
                    $v2['img'] = getImageInfo($v2['value']);
                    $tag[] = $v2;
                }
            }
        }
        $date = date('Y-m-d', $card_model->wedding_time);
        $time = date('H:i', $card_model->wedding_time);
        $card_model->date = $date;
        $card_model->time = $time;
        $card_model->cover = getImageInfo($card_model->cover);
        $card_model->wedding_video_cover = getImageInfo($card_model->wedding_video_cover);
        $card_model->has_video = (bool)$theme->has_video;
        $card_model->video_switch = (bool)$card_model->video_switch;
        unset($card_model->module_data);
        unset($card_model->theme_id);
        unset($card_model->wedding_time);
        return ['res'=>0, 'data'=>['form'=>$card_model, 'tag'=>$tag, 'music_list'=>$music_list]];
    }

    /**
     * 获取用户的卡信息
     *
     * @return array
     * @throws CardMissException
     */
    public function getUserCardAll() {
        $uid = Token::getCurrentUid();
        $card_model = CardModel::field('id,theme_id,user_id,bride_name,bridegroom_name,wedding_time')
            ->with([
                'theme' =>  function ($query) {
                    $query->withField('id,name,preview,bg_color');
                }
            ])
            ->where(['user_id'=>$uid])
            ->select()
        ;
        if ($card_model) {
            return ['res'=>0, 'data'=>$card_model];
        }
        throw new CardMissException();
    }

    /**
     * 覆盖制作
     *
     * @param Request $request
     * @return array
     * @throws CardMissException
     */
    public function coverMakeCard(Request $request)
    {
        (new CardCoverMake())->goCheck();
        $card_id  = $request->post('card_id');
        $theme_id = $request->post('theme_id');
        $card_model = CardModel::find($card_id);
        if ($card_model) {
            $module_model = CardModel::where(['theme_id'=>$theme_id, 'is_theme'=>1])->find();
            $card_model->theme_id           = $theme_id;
            $card_model->wedding_time       = time() + 864000; // 当前时间 + 10天
            $card_model->bride_name         = $module_model->bride_name;
            $card_model->bride_phone        = $module_model->bride_phone;
            $card_model->bridegroom_name    = $module_model->bridegroom_name;
            $card_model->bridegroom_phone   = $module_model->bridegroom_phone;
            $card_model->cover              = $module_model->cover;
            $card_model->flip               = $module_model->flip;
            $card_model->longitude          = $module_model->longitude;
            $card_model->latitude           = $module_model->latitude;
            $card_model->music_id           = $module_model->music_id;
            $card_model->wedding_address    = $module_model->wedding_address;
            $card_model->module_data        = $module_model->module_data;
            $card_model->config_id          = $module_model->config_id;
            $card_model->wedding_video      = $module_model->wedding_video;
            $card_model->wedding_video_cover= $module_model->wedding_video_cover;
            $card_model->isUpdate(true)->save();
            return ['res'=>0];
        }
        throw new CardMissException();
    }

    /**
     * 创建一份请柬 ｜模板 ==复制==> 请柬
     *
     * @param Request $request
     * @return array
     * @throws CardMissException
     * @throws CreateCardException
     */
    public function createCard(Request $request)
    {
        (new CardCreate())->goCheck();
        $uid = Token::getCurrentUid();
        $theme_id = $request->post('theme_id');
        $module_model = CardModel::where(['theme_id'=>$theme_id, 'is_theme'=>1])->find();
        if ($module_model) {
            $card_model = new CardModel();
            $card_model->user_id            = $uid;
            $card_model->theme_id           = $theme_id;
            $card_model->is_theme           = 0;
            $card_model->wedding_time       = time() + 864000; // 当前时间 + 10天

            $card_model->bride_name         = $module_model->bride_name;
            $card_model->bride_phone        = $module_model->bride_phone;
            $card_model->bridegroom_name    = $module_model->bridegroom_name;
            $card_model->bridegroom_phone   = $module_model->bridegroom_phone;
            $card_model->cover              = $module_model->cover;
            $card_model->flip               = $module_model->flip;
            $card_model->longitude          = $module_model->longitude;
            $card_model->latitude           = $module_model->latitude;
            $card_model->music_id           = $module_model->music_id;
            $card_model->wedding_address    = $module_model->wedding_address;
            $card_model->module_data        = $module_model->module_data;
            $card_model->config_id          = $module_model->config_id;
            $card_model->wedding_video      = $module_model->wedding_video;
            $card_model->wedding_video_cover= $module_model->wedding_video_cover;
            $card_model->isUpdate(false)->save();
            if ($card_model->id) {
                $user_model = UserModel::find($uid);
                $user_model->vip = $user_model->vip + 1;
                $user_model->isUpdate(true)->save();
                return ['res'=>0];
            }
            throw new CreateCardException();
        }
        throw new CardMissException();
    }

    public function saveVoice(Request $request)
    {
        $file = $request->file('voice');
        if ($file) {
            $path = ROOT_PATH . 'public' . DS . 'card/voice';
            $info = $file->move($path);
            $file_path = $info->getSaveName();
            $silk = $path . '/' . $file_path;
            $webm = str_replace('silk', 'webm', $silk);
            $content = file_get_contents($silk);
            $baseSilk = base64_decode(str_replace("data:audio/webm;base64,", '', $content));
            file_put_contents($webm, $baseSilk);
            $url = str_replace('silk', 'webm', $file_path);
            return $url;
        }
        throw new Exception('上传有误');

    }
}