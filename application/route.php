<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post( // 聚会活动提交
    'api/:version/activity_submit',
    'api/:version.Activity/saveActivity'
);
Route::get( // 获取聚会活动列表
    'api/:version/activity_list/:id',
    'api/:version.Activity/getActivityList',
    ['id' => '\d+']
);
Route::get( // 获取聚会活动详情
    'api/:version/activity_info/:id',
    'api/:version.Activity/getActivityInfo',
    ['id' => '\d+']
);
Route::post( // 保存 用户关联聚会
    'api/:version/save_activity_user',
    'api/:version.Activity/saveActivityUser'
);
Route::post( // 解密
    'api/:version/activity/encrypt',
    'api/:version.Activity/enCryptedData'
);
Route::get( // 聚会信息
    'api/:version/info/:user_id/:activity_id',
    'api/:version.Info/getUserActivityInfo',
    ['user_id' => '\d+', 'activity_id' => '\d+']
);
Route::get( // 照片信息
    'api/:version/picture/:id',
    'api/:version.Picture/getImageInfo',
    ['id' => '\d+']
);
Route::post( // 保存照片名
    'api/:version/save_picture_name',
    'api/:version.Picture/saveImageName'
);
Route::get( // 获取七牛上传TOKEN
    'api/:version/upload_token',
    'api/:version.Activity/getUploadToken'
);
Route::post( // 保存上传至七牛图片
    'api/:version/save_image',
    'api/:version.Picture/saveQiniuImage'
);
Route::post( // 保存聚会图片关联信息
    'api/:version/save_activity_image',
    'api/:version.Activity/saveActivityImage'
);
Route::post( // 保存参加聚会人员信息
    'api/:version/save_coming',
    'api/:version.Activity/saveUserComing'
);
Route::get('api/:version/game/all', 'api/:version.Game/getAllGames');   // 获取所有游戏列表

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/invitation/submit', 'api/:version.Invitation/postInvitation');

Route::get('api/:version/article/:id', 'api/:version.Article/getArticle');

Route::get('api/:version/template/all', 'api/:version.Template/getAllTemplates');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');      // 获取TOKEN

Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken'); // 验证TOKEN
Route::post('api/:version/token/info', 'api/:version.Token/saveUserInfo'); // 验证TOKEN

Route::group('amos', function(){ // 后台以amos开始
    // 分类
    Route::group('category', [
        'index'         =>  ['admin/Category/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Category/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Category/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Category/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Category/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Category/delete',      ['method' => 'post'] ],
        'change_image'  =>  ['admin/Category/changeImage', ['method' => 'post'] ]
    ]);
    // 模板
    Route::group('template', [
        'index'         =>  ['admin/Template/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Template/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Template/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Template/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Template/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Template/delete',      ['method' => 'post'] ],
        'change_image'  =>  ['admin/Template/changeImage', ['method' => 'post'] ]
    ]);
    // 文章
    Route::group('article', [
        'index'         =>  ['admin/Article/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Article/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Article/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Article/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Article/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Article/delete',      ['method' => 'post'] ],
        'change_image'  =>  ['admin/Article/changeImage', ['method' => 'post'] ]
    ]);
    // banner
    Route::group('banner', [
        'index'         =>  ['admin/Banner/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Banner/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Banner/addProcess',  ['method' => 'post'] ],
        'position'           =>  ['admin/Banner/position',         ['method' => 'get']  ],
        'position_process'   =>  ['admin/Banner/positionProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Banner/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Banner/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Banner/delete',      ['method' => 'post'] ],
        'change_image'  =>  ['admin/Banner/changeImage', ['method' => 'post'] ]
    ]);

    // 图片
    Route::group('image', [
        'upload/:table/:tid/:pid/'  =>  ['admin/Image/upload',           ['method' => 'get'], ['pid' => '\d+', 'tid'=>'\d+'] ],
        'upload_process'            =>  ['admin/Image/uploadProcess',    ['method' => 'post'] ],
        'mkdir'                     =>  ['admin/Image/mkdir',            ['method' => 'post'] ],
        'delete'                    =>  ['admin/Image/delete',           ['method' => 'post'] ],
        'rename'                    =>  ['admin/Image/rename',           ['method' => 'post'] ],
    ]);

    // 角色
    Route::group('role', [
        'index'         =>  ['admin/Role/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Role/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Role/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Role/edit',        ['method' => 'get'], ['id' => '\d+'] ],
        'edit_process'  =>  ['admin/Role/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Role/delete',      ['method' => 'post'] ],
        'auth/:id'      =>  ['admin/Role/auth',        ['method' => 'get'], ['id' => '\d+'] ],
        'authProcess'   =>  ['admin/Role/authProcess', ['method' => 'post'] ],
    ]);
    // 节点
    Route::group('node', [
        'index'         =>  ['admin/Node/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Node/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Node/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Node/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Node/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Node/delete',      ['method' => 'post'] ],
    ]);
    // 用户
    Route::group('admin', [
        'index'         =>  ['admin/Admin/index',       ['method' => 'get']  ],
        'add'           =>  ['admin/Admin/add',         ['method' => 'get']  ],
        'add_process'   =>  ['admin/Admin/addProcess',  ['method' => 'post'] ],
        'edit/:id'      =>  ['admin/Admin/edit',        ['method' => 'get'], ['id' => '\d+']],
        'edit_process'  =>  ['admin/Admin/editProcess', ['method' => 'post'] ],
        'delete'        =>  ['admin/Admin/delete',      ['method' => 'post'] ],
        'login'         =>  ['admin/Admin/login',       ['method' => 'get'] ],
        'login_process' =>  ['admin/Admin/loginProcess',['method' => 'post'] ],
        'logout'        =>  ['admin/Admin/logout',      ['method' => 'post'] ],
        'change_image'  =>  ['admin/Admin/changeImage', ['method' => 'post'] ],
    ]);
    // 系统
    Route::group('system', [
        'dashboard'     =>  ['admin/System/dashboard',  ['method' => 'get'] ],
        'test'          =>  ['admin/System/test',       ['method' => 'get'] ],
        'uptoken'       =>  ['admin/System/upToken',    ['method' => 'get'] ],
        'callback'      =>  ['admin/System/callback',   ['method' => 'post'] ],
        'upload'        =>  ['admin/System/upload',     ['method' => 'get'] ],
        'image'         =>  ['admin/System/image',      ['method' => 'get'] ],
    ]);






//    ':name' => ['Blog/read', ['method' => 'post'], ['id' => '\d+']],
},['ext'=>'html']);