<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/25
 * Time: 8:05
 */

namespace app\common\model;


class ArticleImage extends BaseModel
{
    protected $hidden = ['id', 'image_id', 'article_id', 'create_time', 'delete_time', 'update_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

}