<?php

namespace app\common\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['banner_id', 'create_time', 'delete_time', 'update_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function banner()
    {
        return $this->hasOne('Banner', 'id', 'banner_id');
    }
}
