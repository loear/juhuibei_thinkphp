<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/1/13
 * Time: 23:17
 */

namespace app\home\controller;

use app\api\validate\IDMustBePostiveInt;
use app\common\model\Card as CardModel;
use think\Controller;
use think\Request;

class Card extends Controller
{
    /**
     * 展示邀请卡
     *
     * @param $id
     * @return \think\response\View
     */
    public function index($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::with('theme')->find($id);
        $title = $card_model->bridegroom_name . ' ❤ ' . $card_model->bride_name . ' の 婚礼请柬';
        $global_css = $card_model->theme->global_css;
        $site_config_arr = [
            'theme_id'=>$card_model->theme_id,
            'card_id'   =>  $id,
            'user_id'   =>  input('?get.user_id') ? input('get.user_id') : 0,
            'cover'     =>  $card_model->cover
        ];
        $site_config = json_encode($site_config_arr);
        $this->assign(compact('global_css', 'site_config', 'title'));
        return view();
    }

    /**
     *
     */
    public function sendBless(Request $request)
    {
        $data = $request->post();


    }

}