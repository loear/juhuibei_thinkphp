<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/25
 * Time: 9:22
 */

namespace app\common\model;


class RoleAdmin extends BaseModel
{
    protected $hidden = ['id', 'create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    public function node()
    {
        return $this->belongsTo('Node', 'node_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo('Role', 'role_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id');
    }




}