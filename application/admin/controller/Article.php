<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:27
 */

namespace app\admin\controller;
use app\common\model\Article as ArticleModel;
use think\Request;


class Article extends BaseController
{
    public function index()
    {
        $model = ArticleModel::getArticleAll();
        $article = $model->toArray();
        $this->assign(compact('article'));
        return view();
    }

    public function add()
    {
        return view();
    }

    public function addProcess(Request $request)
    {
        if ($request->isPost()) {
            $model = new ArticleModel();
            $model->title       = $request->post('title');
            $model->description = $request->post('description');
            $model->content     = $request->post('content');
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function edit($id)
    {
        $model = new ArticleModel();
        $data = $model->find($id);
        return view('edit', compact('data'));
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $model = ArticleModel::find($request->post('id'));
            $model->title       = $request->post('title');
            $model->description = $request->post('description');
            $model->content     = $request->post('content');
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function delete(Request $request)
    {
        if ($request->post('id')) {
            ArticleModel::destroy($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function changeImage(Request $request)
    {
        $tid = $request->post('tid');
        $id = $request->post('id');
        if ($tid && $id) {
            $model = ArticleModel::find($tid);
            // 先删除旧的关联，再添加文章分类表的关联
            $model->items()->where(['article_id'=>$model->id])->delete();
            $model->items()->save([
                'article_id' =>  $model->id,
                'image_id'   =>  $id,
            ]);
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
        }
        return json(['status' => 'failed', 'code'  =>  '10000']);
    }

}