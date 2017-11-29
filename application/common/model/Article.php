<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/25
 * Time: 7:55
 */

namespace app\common\model;


class Article extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    public function items()
    {
        return $this->hasMany('article_image', 'article_id', 'id');
    }

    public static function getArticleById($id)
    {
        return self::with(['items', 'items.img'])->find($id);
    }

    public static function getArticleAll()
    {
        return self::with(['items', 'items.img'])->select();
    }

}