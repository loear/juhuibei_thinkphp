<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/24
 * Time: 9:50
 */

namespace app\api\controller\v1;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\ImageNameSave;
use app\api\validate\QiniuImageSave;
use app\common\model\ActivityImage as ActivityImageModel;
use app\common\model\Image as ImageModel;
use app\lib\exception\ActivityException;
use app\lib\exception\ImageMissException;
use think\Request;

class Picture
{
    /**
     * 将七牛图片地址保存到Image表
     *
     * @param Request $request
     * @return array
     */
    public function saveQiniuImage(Request $request)
    {
        (new QiniuImageSave())->goCheck();
        $url = $request->post('url');
        $model = new ImageModel();
        $model->url     = $url;
        $model->type    = 2;
        $model->name    = '';
        $model->save();
        if ($model->id) {
            return ['res'=>0, 'data'=>$model->id];
        }
        throw new ActivityException();
    }

    /**
     * 获取照片信息
     *
     * @param $image_id
     * @return array
     */
    public function getImageInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $data = ActivityImageModel::with([
            'userInfo'  =>  function($query){$query->withField('id,nickname,username,avatar_url,phone');},
            'imgInfo'   =>  function($query){$query->withField('id,url,type');}
        ])
            ->where(['image_id'=>$id])
            ->find();
        if ($data) {
            $data->_data = date('Y年m月d日', strtotime($data->update_time));
            return ['res' => 0, 'data' => $data];
        }
        throw new ImageMissException();
    }

    /**
     * 保存照片名
     *
     * @param Request $request
     * @return array
     */
    public function saveImageName(Request $request)
    {
        (new ImageNameSave())->goCheck();
        $image_id = $request->post('image_id');
        $name     = $request->post('name');
        $model = ActivityImageModel::where(['image_id'=>$image_id])->find();
        if ($model->id) {
            $model->name = $name;
            $model->save();
            return ['res'=>0, 'data'=>'保存成功'];
        }
        throw new ActivityException();
    }



}