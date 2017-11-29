<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:20
 */

namespace app\admin\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();


    }

    public function welcome()
    {
        echo  1;

        return $this->fetch();
    }

}