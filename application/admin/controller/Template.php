<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/6/28
 * Time: 7:28
 */

namespace app\admin\controller;
use app\common\model\Template as TemplateModel;
use think\Request;


class Template extends BaseController
{
    public function index()
    {
        $templates = TemplateModel::all([], 'img');
        $this->assign(compact('templates'));
        return view();
    }

    public function add()
    {
        return view();
    }

    public function addProcess(Request $request)
    {
        if ($request->isPost()) {
            $model = new TemplateModel();
            $model->title       = $request->post('title');
            $model->description = $request->post('description');
            $model->user_id     = 1;
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
        $model = new TemplateModel();
        $data = $model->find($id);
        return view('edit', compact('data'));
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $model = TemplateModel::find($request->post('id'));
            $model->title       = $request->post('title');
            $model->description = $request->post('description');
            $model->user_id     = 1;
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
            TemplateModel::destroy($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '10008']);
    }

    public function changeImage(Request $request)
    {
        $tid = $request->post('tid');
        $id = $request->post('id');
        if ($tid && $id) {
            $model = TemplateModel::find($tid);
            $model->image_id = $id;
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
        }
        return json(['status' => 'failed', 'code'  =>  '10000']);
    }


}