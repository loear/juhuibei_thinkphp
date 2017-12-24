<?php

namespace app\common\model;

use app\common\lib\Data;
use traits\model\SoftDelete;

class Image extends BaseModel
{
    use SoftDelete;

    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    public function addProcess($pid = '0', $url = '', $type = '1', $name = '')
    {
        $data = [
            'pid'   =>  $pid,
            'url'   =>  $url,
            'type'  =>  $type,
            'name'  =>  $name,
        ];
        $result = $this->save($data);
        if ($result !== false ) return true;
        return false;
    }

    /**
     * 获取全部数据
     * @param string $type      tree获取树形结构 level获取层级结构
     * @param string $order     排序方式
     * @param string $name      字段名
     * @param string $child     主键id
     * @param string $parent    父id
     * @return array|mixed
     */
    public static function getLevelData($data, $child='id')
    {
        return Data::channelLevel($data, 0, '&nbsp;', $child);
    }

    /**
     * 根据子id获取所有父级
     * @param $sid
     * @return array
     */
    public static function getParentChannel($sid)
    {
        $data = self::where(['type'=>3])->select()->toArray();
        return Data::parentChannel($data, $sid, $fieldPri = 'id', $fieldPid = 'pid');
    }
}
