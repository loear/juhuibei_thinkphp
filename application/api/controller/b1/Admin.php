<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/5/10
 * Time: 11:51
 */

namespace app\api\controller\b1;
use app\common\model\b1\Category as CategoryModel;
use app\common\model\b1\Image as ImageModel;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Exception;
use think\Request;

class Admin
{
    public function home()
    {
        $category_model = CategoryModel::field('id,name,sort')->order('sort desc')->select();
        return view('home', ['list'=>$category_model->toArray()]);
    }

    public function saveImage(Request $request)
    {
        $category_id = $request->post('category', 0);
        $name        = $request->post('name/a', []);
        $files       = $request->file('images');

        if (!$category_id || !$name || !$files) throw new Exception('数据出错!');

        require_once '../vendor/qiniu-php-sdk/autoload.php';
        $accessKey  = config('qiniu.access_key');
        $secretKey  = config('qiniu.secret_key');
        $bucket     = config('qiniu.bucket_b1');
        $domain     = config('qiniu.domain_b1');
        $auth       = new Auth($accessKey, $secretKey);
        $token      = $auth->uploadToken($bucket);
        $uploadMgr  = new UploadManager();
        foreach ($files as $k => $file) {
            $file_path = $file->getRealPath();
            $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
            $key = substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
            list($ret, $err) = $uploadMgr->putFile($token, $key, $file_path);
            if ($err !== null) {
                return ['res'=>1, 'msg'=>$err, 'data'=>''];
            } else {
                $model = new ImageModel();
                $model->category_id = $category_id;
                $model->name        = $name[$k];
                $model->url         = $domain .'/'. $ret['key'];
                $model->save();
            }
        }

        echo '<script>alert("上传成功!");location.replace("/api/b1/emot/image");</script>';die;
    }

    public function image()
    {
        $image_model = ImageModel::order('create_time desc')->limit('20')->select();
        return view('image', ['list'=>$image_model]);
    }

}