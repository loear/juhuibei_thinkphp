<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/24
 * Time: 9:50
 */

namespace app\api\controller\v1;
use app\common\model\ActivityImage as ActivityImageModel;
use app\common\model\Image as ImageModel;
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
        $url = $request->post('url');
        if ($url) {
            $model = new ImageModel();
            $model->url     = $url;
            $model->type    = 2;
            $model->name    = '';
            $model->save();
            return ['res'=>0, 'data'=>$model->id];
        }
        return ['res'=>-1, 'msg'=>'URL不能为空'];
    }

    /**
     * 获取照片信息
     *
     * @param $image_id
     * @return array
     */
    public function getImageInfo($image_id)
    {
        $data = ActivityImageModel::with([
            'userInfo'  =>function($query){$query->withField('id,nickname,username,avatar_url,phone');},
        'imgInfo' =>function($query){$query->withField('id,url,type');}
        ])
            ->where(['image_id'=>$image_id])
        ->find();
        if ($data) {
            $data['_data'] = date('Y年m月d日', strtotime($data->update_time));
            return ['res' => 0, 'data' => $data];
        }
        return ['res' => 1, 'msg' => '获取数据失败'];
    }

    /**
     * 保存照片名
     *
     * @param Request $request
     * @return array
     */
    public function saveImageName(Request $request)
    {
        $image_id = $request->post('image_id');
        $name = $request->post('name');
        $model = ActivityImageModel::where(['image_id'=>$image_id])->find();
        $model->name = $name;
        $model->save();
        if ($model->id) {
            return ['res'=>0, 'data'=>$model->id];
        }
        return ['res'=>-1, 'msg'=>'保存失败'];
    }



}