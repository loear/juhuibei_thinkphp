<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/27
 * Time: 16:22
 */

namespace app\common\model;


class ApiLog extends BaseModel
{
    /**
     * API日志
     * @param $info
     * @param $code
     * @param $params
     * @return bool
     */
    public function recordLog($info, $code, $json = '')
    {
        $this->info         = $info;
        $this->result_code  = $code;
        $this->opera_time   = time();
        $this->prama_json   = $json;
        $this->save();
        return true;
    }

}