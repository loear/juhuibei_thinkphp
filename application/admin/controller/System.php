<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/27
 * Time: 10:21
 */

namespace app\admin\controller;

use think\Session;

class System extends BaseController
{
    public function dashboard()
    {
        $now_time = time();
        $php_os = PHP_OS;
        $this->assign(compact('now_time', 'php_os'));
        return view();
    }

}