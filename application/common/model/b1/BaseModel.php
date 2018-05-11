<?php
/**
 * Created by PhpStorm.
 * User: Guo
 * Date: 2018/5/10
 * Time: 9:41
 */

namespace app\common\model\b1;


use think\Model;

class BaseModel extends Model
{
    protected $connection = [
        // 数据库类型
        'type'      =>  'mysql',
        // 数据库连接DSN配置
        'dsn'       =>  '',
        // 服务器地址
        'hostname'  =>  'localhost',
        // 数据库名
        'database'  =>  'ng_emoticon',
        // 数据库用户名
        'username'  =>  'guo',
        // 数据库密码
        'password'  =>  'sn_123456',
        // 数据库连接端口
        'hostport'  =>  '3306',
        // 数据库连接参数
        'params'    =>  [],
        // 数据库编码默认采用UTF8
        'charset'   =>  'utf8',
        // 数据库表前缀
        'prefix'    =>  'ng_',
        // 数据集返回类型
        'resultset_type' => 'collection',
    ];

}