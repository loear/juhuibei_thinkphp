<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 11:21
 */

namespace app\common\model;


class Banner extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public static function getBannerById($id)
    {
        return self::with(['items', 'items.img'])->find($id);
    }

    public static function getBannerAll()
    {
        return BannerItem::with(['banner', 'img'])->select();
    }

    public static function getItemById($id)
    {
        return BannerItem::with(['banner', 'img'])->find($id);
    }

    public static function saveItem($id, $type, $keywords)
    {
        return BannerItem::where(['id'=>$id])
            ->update([
                'type'      =>  $type,
                'keywords'  =>  $keywords
            ]);
    }

    public static function destroyItem($id)
    {
        return BannerItem::destroy($id);
    }

    public static function saveImageId($tid, $id)
    {
        return BannerItem::where(['id'=>$tid])->update(['image_id'=>$id]);
    }

}