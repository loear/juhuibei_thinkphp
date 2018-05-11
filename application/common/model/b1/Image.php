<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/5/10
 * Time: 9:43
 */

namespace app\common\model\b1;


use traits\model\SoftDelete;

class Image extends BaseModel
{
    use SoftDelete;

    public function category()
    {
        return $this->belongsTo('Category', 'category_id', 'id');
    }

    protected $hidden = [
        'delete_time',
        'update_time',
        'create_time'
    ];

}