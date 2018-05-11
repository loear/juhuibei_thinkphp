<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/5/10
 * Time: 9:43
 */

namespace app\common\model\b1;


use traits\model\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;

    protected $hidden = [
        'delete_time',
        'update_time',
        'create_time'
    ];

    public function img()
    {
        return $this->hasMany('Image', 'category_id', 'id');
    }

}