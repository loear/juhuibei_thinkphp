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

class Card extends Controller
{
    public function index($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $card_model = CardModel::with('theme')->find($id);
        $global_css = $card_model->theme->global_css;
        $site_config_arr = [
            'theme_id'=>$card_model->theme_id,
            'card_id'   =>  $id,
//            'user_id'   =>  $card_model->user_id,
            'user_id'   =>  0,
            'cover'     =>  $card_model->cover
        ];
        $site_config = json_encode($site_config_arr);
        $this->assign(compact('global_css', 'site_config'));
        return view();
    }

}