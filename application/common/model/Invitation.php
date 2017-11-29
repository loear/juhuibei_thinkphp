<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/23
 * Time: 6:48
 */

namespace app\common\model;


class Invitation extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];

    protected $autoWriteTimestamp = true;


}