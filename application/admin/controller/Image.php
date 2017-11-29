<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:28
 */

namespace app\admin\controller;
use app\admin\validate\ImageNew;
use app\common\model\Image as ImageModel;
use think\Request;

class Image extends BaseController
{
    /**
     * 图片关联页面
     * @param $table
     * @param $tid
     * @param $pid
     * @return \think\response\View
     */
    public function upload($table, $tid, $pid)
    {
        $images = ImageModel::where(['pid'=>$pid])->order('type desc')->paginate(8);
        $data = $images->toArray()['data'];
        $page = $images->render();
        $this->assign(compact('data', 'pid', 'page', 'table', 'tid'));
        return view();
    }

    /**
     * 上传
     * @param Request $request
     * @return \think\response\Json
     */
    public function uploadProcess(Request $request)
    {
        $file = request()->file('file');
        $file_name = $file->getInfo()['name'];
        (new ImageNew())->goCheck();
        $pid = $request->post('pid', '');
        if ($pid > 0) {
            $data = ImageModel::getParentChannel($pid);
            $p_dir = '';
            foreach ($data as $v) {
                $p_dir = DS . $v['name'] . $p_dir;
            }
        }
        if (!$file) return json(['status'=>'failed', 'code'=>'10006'], 400);
        // 移动到框架应用根目录/public/images_cover/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'images_cover' . $p_dir, $file_name, false); // 不覆盖同文件名
        if ($info) {
            $base_dir = ROOT_PATH . 'public' . DS;
            $image = \think\Image::open($base_dir . 'images_cover' . $p_dir . DS . $info->getSaveName());
            $thumb_name = $info->getFilename();
            $image->thumb(config('image_max_width'), config('image_max_height'), \think\Image::THUMB_SCALING)
                ->save($base_dir . 'images'  . $p_dir . DS . $thumb_name);
            $url = $p_dir . DS . $thumb_name;
            $res = (new ImageModel())->addProcess($pid, $url, '1', $file->getInfo()['name']);
            if (!$res) return json(['status' => 'failed', 'code'  =>  '10000'], 400);
            return json(['status' => 'success', 'code'  =>  '00000']);
        }else{
            // 上传失败获取错误信息->filename
            echo $file->getError();
        }
    }

    /**
     * 创建文件夹
     * @param Request $request
     * @return \think\response\Json
     */
    public function mkdir(Request $request)
    {
        (new ImageNew())->goCheck();
        $base_dir = ROOT_PATH . 'public' . DS . 'images';
        $base_cover_dir = ROOT_PATH . 'public' . DS . 'images_cover';
        $pid = $request->post('pid', '');
        $folder = $request->post('folder', '');

        if ($pid == 0) {
            $dir = $base_dir . DS .$folder;
            $cover_dir = $base_cover_dir . DS .$folder;
            $url = DS . $folder;
        } else {
            $data = ImageModel::getParentChannel($pid);
            $p_dir = '';
            foreach ($data as $v) {
                $p_dir = DS . $v['name'] . $p_dir;
            }
            $dir = $base_dir . $p_dir . DS . $folder;
            $cover_dir = $base_cover_dir . $p_dir . DS . $folder;
            $url = $p_dir . DS . $folder;
        }
        $dir = iconv("UTF-8", "GBK", $dir);
        if (!file_exists($dir)){
            mkdir($dir, 0777, true);
            mkdir($cover_dir, 0777, true);
            $res = (new ImageModel())->addProcess($pid, $url, '3', $folder);
            if (!$res) return json(['status' => 'failed', 'code'  =>  '10000'], 400);
            return json(['status' => 'success', 'code'  =>  '00000']);
        } else {
            return json(['status' => 'failed', 'code'  =>  '10001'], 400);
        }

    }

    /**
     * 删除
     * @param Request $request
     * @return \think\response\Json
     */
    public function delete(Request $request)
    {
        $data_arr = $request->post()['image'];
        if (is_array($data_arr)) {
            foreach ($data_arr as $id) {
                if ($id) {
                    $model = ImageModel::get(['pid'=>$id]);
                    if (!$model) {
                        $model_image = ImageModel::find($id);
                        $path = ROOT_PATH . 'public' . DS . 'images' . $model_image->url;
                        $path_cover = ROOT_PATH . 'public' . DS . 'images_cover' . $model_image->url;
                        if ($model_image->type == 3) {
                            if (!rmdir($path) || !rmdir($path_cover)) {
                                return json(['status' => 'failed', 'code'  =>  '10002']);
                            }
                        } else if ($model_image->type == 1) {
                            if (is_file($path) && is_file($path_cover)) {
                                if(!unlink($path) || !unlink($path_cover)) {
                                    return json(['status' => 'failed', 'code'  =>  '10002']);
                                }
                            }
                        }
                        $model_image->delete(true);
                        continue;
                    }
                    return json(['status' => 'failed', 'code'  =>  '10003'], 400);
                }
                return json(['status' => 'failed', 'code'  =>  '10004'], 400);
            }
            return json(['status' => 'success', 'code'  =>  '00000']);
        }
        return json(['status' => 'failed', 'code'  =>  '10005'], 400);
    }

    /**
     * 重名名
     * @param Request $request
     * @return \think\response\Json
     */
    public function rename(Request $request)
    {
        $id = $request->post('id');
        $name = $request->post('name');
        if ($id && $name) {
            $model = ImageModel::find($id);
            if ($model && $model->type != 3) {
                $old_url = str_replace(config('setting.img_prefix'), '', $model->url);
                $pathinfo = pathinfo($old_url);
                $model->url = $pathinfo['dirname']  . DS . $name . '.' . $pathinfo['extension'];
                $model->name = $name . '.' . $pathinfo['extension'];
                $model->save();
                $new_url = str_replace(config('setting.img_prefix'), '', $model->url);
                $base_dir = ROOT_PATH . 'public' . DS;
                $res = rename($base_dir . 'images' . $old_url, $base_dir . 'images' . $new_url);
                $res_cover = rename($base_dir . 'images_cover' . $old_url, $base_dir . 'images_cover' . $new_url);
                if ($res && $res_cover) {
                    return json(['status' => 'success', 'code'  =>  '00000']);
                }
                return json(['status' => 'failed', 'code'  =>  '10002'], 400);
            }
            return json(['status' => 'failed', 'code'  =>  '10007'], 400);
        }
        return json(['status' => 'failed', 'code'  =>  '100005'], 400);
    }

}