<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/26
 * Time: 7:34
 */

namespace app\common\service;
use app\common\model\RoleAdmin as RoleAdminModel;
use app\common\model\User as UserModel;

use think\Controller;
use think\Session;

class rbac extends Controller
{
    /**
     * 获取管理员具有的权限
     * @param $admin_id 管理员id
     * @return array 含有全部权限的数组
     */
    private function getActionList($admin_id)
    {
        // 构思: 返回数据['back/brand/list', 'back/brand/add']
        $model = new RoleAdminModel();
        $rows = $model
            ->field('a.node action, c.node controller, m.node module')
            ->alias('ra')
            ->join('__ACCESS__ ac', 'ra.role_id=ac.role_id', 'left')
            ->join('__NODE__ a', 'ac.node_id=a.id', 'left') // 动作
            ->join('__NODE__ c', 'a.pid=c.id', 'left') // 控制器
            ->join('__NODE__ m', 'c.pid=m.id', 'left') // 模块
            ->where(['ra.admin_id'=>$admin_id])
            ->select()->toArray();
        ;
        return array_map(function($row){
            return $row['module'] . '/' . $row['controller'] . '/' . $row['action'];
        }, $rows);
    }

    /**
     * 检测当前管理员是否具有权限
     */
    public function checkAccess($route)
    {

        // 判断是否需要认证
        if (in_array($route, config('un_auth_action'))) {
            // 不需要认证即可执行
            return true;
        }

        // 是否认证(是否登录)
        if (!$login = Session::get('login')) {
            // 没有登录认证
            return $this->redirect('admin/Admin/login');
        }

        $model = new RoleAdminModel();
        $count = $model->alias('ra')
            ->join('__ROLE__ r', 'ra.role_id=r.id', 'left')
            ->where(['r.id'=>1, 'ra.admin_id'=>$login['id']])
            ->count();
        // 是否为超级管理员
        if ($count > 0) {
            // 是超级管理员
            return true;// 继续执行
        }

        // 动作是否需要授权
        if (in_array($route, config('allow_action'))) {
            // 不需要
            return true;
        }

        // 判断是否具有权限
        $action_list = $this->getActionList($login['id']);
        if (in_array($route, $action_list)) {
            return true;// 执行
        }
        // 没有授权
        return $this->redirect('admin/System/dashboard');
    }

}