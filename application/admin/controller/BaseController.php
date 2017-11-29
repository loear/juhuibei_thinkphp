<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:18
 */

namespace app\admin\controller;


use think\Controller;
use app\common\service\rbac;
use think\Request;
use think\Session;

class BaseController extends Controller
{

    protected function _initialize()
    {
        $path_arr = explode('/', str_replace('.html', '', $_SERVER['PATH_INFO']));
        $path = $path_arr['0'] . '/' . $path_arr['1'] . '/' . $path_arr['2'];
        if ($path_arr['0'] == '') $path = $path_arr['1'] . '/' . $path_arr['2'] . '/' . $path_arr['3']; // 解决 iis pathinof问题

        $login = Session::get('login');

        $rbac = new rbac();
        $rbac->checkAccess($path);

        $this->assign(compact('path', 'login'));
    }

}