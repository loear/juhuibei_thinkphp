<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/5/10
 * Time: 9:45
 */

namespace app\api\controller\b1;

use app\common\model\b1\Category as CategoryModel;
use app\common\model\b1\Image as ImageModel;

class Emoticon
{
    /**
     * 获取表情
     *
     * @return array
     */
    public function getEmoticonNew()
    {
        $count = ImageModel::where(['is_new'=>1])->count();
        $num = rand(0, ceil($count / 9) - 1);
        $image_model = ImageModel::where(['is_new'=>1])->limit($num * 9 . ',9')->select();
        if ($image_model->isEmpty()) {
            return ['res'=>1];
        } else {
            return ['res'=>0, 'data'=>$image_model];
        }
    }

    /**
     * 获取分类列表
     *
     * @return array
     */
    public function getCategoryList()
    {
        $cat_model = CategoryModel::with([
            'img' => function ($query) {
                $query->limit('9');
            }
        ])->where(['is_hot'=>1])->select();
        if ($cat_model->isEmpty()) {
            return ['res'=>1];
        } else {
            return ['res'=>0, 'data'=>$cat_model];
        }
    }

    public function getCategoryById($id)
    {
        $image_model = ImageModel::hasWhere('category', ['id'=>$id])->order('sort desc')->select();
        if ($image_model->isEmpty()) {
            return ['res'=>1];
        } else {
            return ['res'=>0, 'data'=>$image_model];
        }
    }

    public function getSearchImage($word)
    {
        $image_model = ImageModel::where('name|tags','like','%' . $word. '%')->order('sort desc')->select();
        if ($image_model->isEmpty()) {
            return ['res'=>1];
        } else {
            return ['res'=>0, 'data'=>$image_model];
        }
    }

    public function getHotSearchWord()
    {
        $data = [
            ['word'=>'温暖的弦'],
            ['word'=>'创造101'],
            ['word'=>'奔跑吧兄弟'],
            ['word'=>'极限挑战'],
            ['word'=>'向往的生活'],
            ['word'=>'妈妈是超人'],
            ['word'=>'母亲节'],
            ['word'=>'热血街舞团'],
            ['word'=>'机器人争霸'],
            ['word'=>'冒险王卫斯理'],
            ['word'=>'萌妻食神'],
            ['word'=>'这就是街舞'],
            ['word'=>'四叶鸭']
        ];
        return ['res'=>0, 'data'=>$data];
    }

}