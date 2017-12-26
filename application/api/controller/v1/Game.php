<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/6
 * Time: 10:34
 */

namespace app\api\controller\v1;
use app\common\model\Game as GameModel;
use app\lib\exception\GameException;

class Game
{
    public function getGamesAll()
    {
        $data = GameModel::all([], ['img'=>function($query){$query->withField('id,url,type');}]);

        if ($data->isEmpty()) {
            throw new GameException();
        }
        return ['res'=>0, 'data'=>$data];
    }

}