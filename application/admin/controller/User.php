<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:29
 */

namespace app\admin\controller;


class User extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

}