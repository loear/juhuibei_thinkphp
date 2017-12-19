<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/11
 * Time: 9:08
 */

namespace app\common\model;


use think\Model;

class BaseModel extends Model
{
    public function prefixImgUrl($value, $data)
    {
        $finalUrl = str_replace('\\', '/', $value);
        if ($data['type'] == 1) {
            $finalUrl = config('setting.img_prefix') . $finalUrl;
        } else if ($data['type'] == 2) {
            $finalUrl = 'http://' . $finalUrl;
        }
        return $finalUrl;
    }
}