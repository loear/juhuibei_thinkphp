<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/16
 * Time: 17:23
 */

namespace app\api\controller\v1;



use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BlessMissException;
use think\Db;

class Bless
{
    public function getBlessByCardId($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $bless = Db::table('__BLESS__')
            ->field('b.cate_id,b.content,b.id,b.create_time as timestamp,u.avatar_url as avatar,u.nickname')
            ->alias('b')
            ->join('__USER__ u', 'b.user_id=u.id')
            ->where('b.card_id', $id)
            ->select()
            ->toArray()
        ;
        if (!empty($bless)) {
            return ['data'=>$bless, 'success'=>true];
        }
        throw new BlessMissException();
    }


}