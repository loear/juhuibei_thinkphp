<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/23
 * Time: 10:50
 */

namespace app\admin\controller;

use app\admin\validate\NodeNew;
use app\common\model\Node as NodeModel;
use think\Request;

class Node extends BaseController
{
    public function index()
    {
        $nodes = NodeModel::getTreeData('tree', 'sort_number', 'node');
        /*dump($nodes);
        die;*/
        $this->assign(compact('nodes'));
        return view();
    }

    public function add()
    {
        $nodes = NodeModel::getTreeData('tree', 'sort_number', 'node');
        $this->assign(compact('nodes'));
        return view();
    }

    public function addProcess(Request $request)
    {
        (new NodeNew())->goCheck();
        if ($request->isPost()) {
            $model = new NodeModel();
            $model->node        = $request->post('node');
            $model->nodename    = $request->post('nodename', 0);
            $model->pid         = $request->post('pid');
            $model->sort_number = $request->post('sort_number');
            $model->remark      = $request->post('remark');
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function edit($id)
    {
        $data = NodeModel::find($id);
        $nodes = NodeModel::getTreeData('tree', 'sort_number', 'node');
        $this->assign(compact('data', 'nodes'));
        return view();
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $model = NodeModel::find($request->post('id'));
            $model->node        = $request->post('node');
            $model->nodename    = $request->post('nodename', 0);
            $model->pid         = $request->post('pid');
            $model->sort_number = $request->post('sort_number');
            $model->remark      = $request->post('remark');
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function delete(Request $request)
    {
        if ($request->post('id')) {
            $model = NodeModel::get(['pid'=>$request->post('id')]);
            if (!$model) {
                NodeModel::destroy($request->post('id'));
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10001']);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

}