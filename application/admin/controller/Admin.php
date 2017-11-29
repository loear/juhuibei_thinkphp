<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/21
 * Time: 16:04
 */

namespace app\admin\controller;
use app\common\model\Role;
use app\common\model\RoleUser as RoleUserModel;
use app\common\model\Admin as AdminModel;
use app\common\model\Role as RoleModel;
use think\Request;
use think\Session;

class Admin extends BaseController
{
    public function index()
    {
        $data = AdminModel::with(['roles.role', 'image'])->select();
        $this->assign(compact('data'));
        return view();
    }

    public function add()
    {
        $roles = RoleModel::all();
        $this->assign(compact('roles'));
        return view();
    }

    public function addProcess(Request $request)
    {
        if ($request->isPost()) {
            $model = new AdminModel();
            // 用户名不能重复
            $count = $model->where(['username'=>$request->post('username')])->count();
            if ($count > 0) return json(['status' => 'failed', 'code'  =>  '10002']);
            $model->username = $request->post('username');
            $model->password = password_md5($request->post('password'), '2017');
            $model->nickname = $request->post('nickname');
            $model->email    = $request->post('email');
            $model->phone    = $request->post('phone');
            $model->status   = $request->post('status');
            $model->save();
            if ($model->id) {
                foreach ($request->post('roles/a') as $role_id) {
                    $model->roles()->save([
                        'admin_id'  =>  $model->id,
                        'role_id'   =>  $role_id,
                    ]);
                }
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function edit($id)
    {
        $data = AdminModel::with('roles.role')->find($id);
        $roles = RoleModel::all();
        $this->assign(compact('data', 'roles'));
        return view();
    }

    public function editProcess(Request $request)
    {
        if ($request->post('id')) {
            $model = AdminModel::find($request->post('id'));
            $model->nickname = $request->post('nickname');
            $model->email    = $request->post('email');
            $model->phone    = $request->post('phone');
            $model->status   = $request->post('status');
            $model->save();
            if ($model->id) {
                $model->roles()->where(['admin_id'=>$model->id])->delete();
                foreach ($request->post('roles/a') as $role_id) {
                    $model->roles()->save([
                        'admin_id'   =>  $model->id,
                        'role_id'   =>  $role_id,
                    ]);
                }
                return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $model->id]]);
            }
            return json(['status' => 'failed', 'code'  =>  '10000']);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function delete(Request $request)
    {
        if ($request->post('id')) {
            UserModel::destroy($request->post('id'));
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => ['id' => $request->post('id')]]);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function login()
    {
        $refer_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url("admin/System/dashboard");
        $this->assign(compact('refer_url'));
        return view();
    }

    public function loginProcess(Request $request)
    {
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        $refer_url = $request->post('refer_url', url('admin/System/dashboard'));

        if ($username == '' && $password == '') return json(['status' => 'failed', 'code'  =>  '10000']);

        $admin_data = AdminModel::where([
            'username'  =>  $username,
            'password'  =>  password_md5($password, '2017')
        ])->with('image')->find()->toArray();

        if ($admin_data) {
            $ip = get_client_ip_from_ns();
            AdminModel::where(['username'=>$username])->update([
                'last_login_ip'     =>  $ip,
                'last_login_time'   =>  time()
            ]);
            Session::set('login', $admin_data);
            return json(['status' => 'success', 'code'  =>  '00000', 'result' => $admin_data, 'url'=>$refer_url]);
        }
        return json(['status' => 'failed', 'code'  =>  '99999']);
    }

    public function logout()
    {
        Session::delete('login');
        return json(['status' => 'success', 'code'  =>  '00000']);
    }

    public function changeImage(Request $request)
    {
        $tid = $request->post('tid');
        $id = $request->post('id');
        if ($tid && $id) {
            $model = AdminModel::find($tid);
            $model->image_id = $id;
            $model->save();
            if ($model->id) {
                return json(['status' => 'success', 'code'  =>  '00000']);
            }
        }
        return json(['status' => 'failed', 'code'  =>  '10000']);
    }

}