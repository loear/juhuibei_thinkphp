<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2017/9/24
 * Time: 11:57
 */

namespace app\common\model;


use app\common\lib\Data;
use traits\model\SoftDelete;

class Node extends BaseModel
{
    protected $hidden = ['create_time', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    use SoftDelete;

    /**
     * 获取全部数据
     * @param string $type      tree获取树形结构 level获取层级结构
     * @param string $order     排序方式
     * @param string $name      字段名
     * @param string $child     主键id
     * @param string $parent    父id
     * @return array|mixed
     */
    public static function getTreeData($type='tree', $order='', $name='name', $child='id', $parent='pid')
    {
        // 判断是否需要排序
        if(empty($order)){
            $data = self::all()->toArray();
        }else{
            $data = self::all(function ($query) use ($order){
                $query->order($order,'desc');
            })->toArray();
        }
        // 获取树形或者结构数据
        if($type == 'tree'){
            $data = Data::tree($data, $name, $child, $parent);
            foreach ($data as $k=>$v) {
                $data[$k]['_has_child'] = Data::hasChild($data, $v['id']);
            }
        }elseif($type = "level"){
            $data = Data::channelLevel($data, 0, '&nbsp;', $child);
        }
        return $data;
    }

}