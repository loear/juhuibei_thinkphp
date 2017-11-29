<?php


namespace app\api\controller\v1;

use app\common\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     *
     */
    public function getBanner($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        // $banner = BannerModel::with(['items', 'items.img'])->find($id);
        $banner = BannerModel::getBannerById($id);
        // $banner->hidden(['update_time', 'delete_time']);
        // $banner->visible(['id', 'name']);
        if (!$banner) {
            throw new BannerMissException();
        }

        return $banner;
    }
}