<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/23
 * Time: 10:52
 */

namespace app\common\model;


use traits\model\SoftDelete;

class Role extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    use SoftDelete;

    public function nodes()
    {
        return $this->hasMany('Access', 'role_id', 'id');
    }

    public function admin()
    {
        return $this->hasMany('RoleAdmin', 'role_id', 'id');
    }

}