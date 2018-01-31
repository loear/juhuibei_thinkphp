<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/16
 * Time: 17:23
 */

namespace app\api\controller\v1;



use app\api\service\Token;
use app\api\validate\BlessSave;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\JoinWeddingSave;
use app\lib\exception\BlessMissException;
use app\common\model\Bless as BlessModel;
use think\Db;
use think\Request;

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

    /**
     * 发送祝福
     *
     * @param Request $request
     * @return array
     * @throws BlessMissException
     */
    public function saveBless(Request $request)
    {
        (new BlessSave())->goCheck();
        $uid = Token::getCurrentUid();
        $data = $request->post();
        $count = BlessModel::where(['user_id'=>$uid, 'card_id'=>$data['card_id']])->count();
        if ($count) return ['res'=>1, 'data'=>'已经祝福过了'];
        $bless_model = new BlessModel();
        $bless_model->user_id = $uid;
        $bless_model->card_id = $data['card_id'];
        $bless_model->content = $data['content'];
        $bless_model->cate_id = $data['cate_id'];
        $bless_model->isUpdate(false)->save();
        if ($bless_model->id) {
            return ['res'=>0];
        }
        throw new BlessMissException();
    }

    /**
     * 请柬回执
     *
     * @param Request $request
     * @throws BlessMissException
     */
    public function joinWedding(Request $request)
    {
        (new JoinWeddingSave())->goCheck();
        $data = $request->post();
        $bless_model = BlessModel::where(['user_id'=>$data['user_id'], 'card_id'=>$data['card_id']])
            ->find()
        ;
        if ($bless_model) {
            $bless_model->is_receipt   = 1;
            $bless_model->receipt_num  = $data['part_num'];
            $bless_model->receipt_name = $data['user_name'];
            $bless_model->isUpdate(true)->save();
            return ['success'=>1, 'data'=>['wx_user_id'=>$data['user_id']]];
        }
        throw new BlessMissException();

    }

    /**
     * 获取Card回执信息
     *
     * @param $id
     * @return array
     * @throws BlessMissException
     */
    public function getWeddingList($id)
    {
        $bless_model = BlessModel::field('user_id,card_id,is_receipt,receipt_name,receipt_num')
            ->with('user')
            ->where(['is_receipt'=>1, 'card_id'=>$id])
            ->select()
        ;
        $sum = 0;
        foreach ($bless_model as $v) {
            $sum += $v['receipt_num'];
        }
        if ($bless_model) {
            return ['res'=>0, 'data'=>['wedding_list'=>$bless_model, 'sum'=>$sum]];
        }
        throw new BlessMissException();
    }




}