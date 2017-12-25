<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/12/25
 * Time: 23:29
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePostiveInt;
use app\common\model\User as UserModel;
use app\lib\exception\ActivityException;

class User
{
    public function getUserInfo($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $user_model = UserModel::find($id);
        if ($user_model) {
            return ['res'=>0, 'data'=>$user_model];
        }
        throw new ActivityException();
    }

}