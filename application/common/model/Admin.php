<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 16:46
 */

namespace app\common\model;

use traits\model\SoftDelete;

class Admin extends BaseModel
{
    protected $hidden = ['password', 'email_code', 'create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    use SoftDelete;

    public function roles()
    {
        return $this->hasMany('RoleAdmin', 'admin_id', 'id');
    }

    public function image()
    {
        return $this->hasOne('Image', 'id', 'image_id');
    }

}