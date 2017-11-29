<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/23
 * Time: 10:50
 */

namespace app\admin\controller;

use app\admin\validate\RoleAuth;
use app\admin\validate\RoleNew;
use app\common\model\Role as RoleModel;
use app\common\model\Node as NodeModel;

use think\Request;

class Role extends BaseController
{
    public function index()
    {
        $roles = RoleModel::order('listorder', 'desc')->select();
        $this->assign(compact('roles'));
        return view();
    }

    public function add()
    {
        return view();
    }

    public function addProcess(Request $request)
    {
        (new RoleNew())->goCheck();
        if ($request->isPost()) {
            $model = new RoleModel();
            $model->name        = $request->post('name');
            $model->status      = $request->post('status', 0);
            $model->remark      = $request->post('remark');
            $model->listorder   = $request->post('listorder');
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
        $data = RoleModel::find($id);
        $this->assign(compact('data'));
        return view();
    }

    public function editProcess(Request $request)
    {
        (new RoleNew())->goCheck();
        if ($request->post('id')) {
            $model = RoleModel::find($request->post('id'));
            $model->name        = $request->post('name');
            $model->status      = $request->post('status', 0);
            $model->remark      = $request->post('remark');
            $model->listorder   = $request->post('listorder');
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
            RoleModel::destroy($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function auth($id)
    {
        $nodes = NodeModel::getTreeData('level', 'sort_number');
        $model = new RoleModel();
        $checked = $model->nodes()->where(['role_id'=>$id])->select()->toArray();
        $checked = array_map(function($v){
            return $v = $v['node_id'];
        }, $checked);
        $this->assign(compact('nodes', 'id', 'checked'));
        return view();
    }

    public function authProcess(Request $request)
    {
        (new RoleAuth())->goCheck();
        $model = RoleModel::find($request->post('id'));
        $role_arr = $request->post('node/a');
        $model->nodes()->where(['role_id'=>$model->id])->delete();
        foreach ($role_arr as $v ) {
            $model->nodes()->save([
                'role_id'   =>  $model->id,
                'node_id'   =>  $v
            ]);
        }
        return json(['status' => 'success', 'code'  =>  '00000']);
    }

}