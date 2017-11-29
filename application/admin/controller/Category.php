<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:29
 */

namespace app\admin\controller;
use app\common\model\Category as CategoryModel;
use think\Request;


class Category extends BaseController
{
    public function index()
    {
        $categories = CategoryModel::all([], 'img');
        $this->assign(compact('categories'));
        return view();
    }

    public function add()
    {
        return view();
    }

    public function addProcess(Request $request)
    {
        if ($request->isPost()) {
            $model = new CategoryModel();
            $model->title       = $request->post('title');
            $model->type        = $request->post('type');
            $model->description = $request->post('description');
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
        $model = new CategoryModel();
        $data = $model->find($id);
        return view('edit', compact('data'));
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $model = CategoryModel::find($request->post('id'));
            $model->title       = $request->post('title');
            $model->type        = $request->post('type');
            $model->description = $request->post('description');
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
            CategoryModel::destroy($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function changeImage(Request $request)
    {
        $tid = $request->post('tid');
        $id = $request->post('id');
        if ($tid && $id) {
            $model = CategoryModel::find($tid);
            $model->head_image_id = $id;
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
        }
        return json(['status' => 'failed', 'code'  =>  '10000']);
    }

}