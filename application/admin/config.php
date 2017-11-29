<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/29
 * Time: 13:58
 */

return [
    'image_max_width'    =>  816,    // md缩略图的高
    'image_max_height'   =>  2000,   // md缩略图的高

    'un_auth_action' => [
        'amos/admin/login',
        'amos/admin/login_process',
    ],
    'allow_action'  =>  [
        'amos/system/dashboard',
        'amos/admin/logout',
    ],
    // 七牛配置信息
    'qiniu_access_key'  =>  'H82tzsO0bwxy2ywpnSb41zyWam1Tw_-rlfEsBzc-',
    'qiniu_secret_key'  =>  'UuW0vdJ9c4Bef80OeRedoISaheJTwWBc4mvfYJR3',
    'qiniu_bucket_name' =>  '6mgcn',
];