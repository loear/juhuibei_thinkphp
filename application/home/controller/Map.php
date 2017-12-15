<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/15
 * Time: 17:53
 */

namespace app\home\controller;


use think\Controller;

class Map extends Controller
{
    public function navigation ()
    {
        $key = config('amap_key');
        $this->assign(compact('key'));
        return view();

    }

}