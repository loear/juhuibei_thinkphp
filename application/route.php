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
Route::get('api/:version/test','api/:version.Info/test');
Route::get('api/:version/insert/:id','api/:version.Theme/insertById', ['id' => '\d+']);
Route::get('api/:version/bat','api/:version.Theme/bat');


Route::get('card/:id', 'api/home/Card/index', ['id' => '\d+']);

Route::group('api/:version', [
    '/activity_submit'     => ['api/:version.Activity/saveActivity',     ['method' => 'post']],                   // 聚会活动提交
    '/activity_list/:id'   => ['api/:version.Activity/getActivityList',  ['method' => 'get'], ['id' => '\d+']],   // 获取聚会活动列表
    '/activity_info/:id'   => ['api/:version.Activity/getActivityInfo',  ['method' => 'get'], ['id' => '\d+']],   // 获取聚会活动详情
    '/save_activity_user'  => ['api/:version.Activity/saveActivityUser', ['method' => 'post']],                   // 保存 用户关联聚会
    '/info/:user_id/:activity_id'=> [
        'api/:version.Info/getUserActivityInfo',
        ['method' => 'get'],
        ['user_id' => '\d+', 'activity_id' => '\d+']
    ],   // 聚会信息
    '/picture/:id'         => ['api/:version.Picture/getImageInfo',       ['method' => 'get'], ['id' => '\d+']],   // 照片信息
    '/save_picture_name'   => ['api/:version.Picture/saveImageName',      ['method' => 'post']],                   // 保存照片名
    '/upload_token'        => ['api/:version.Activity/getUploadToken',    ['method' => 'get']],                    // 获取七牛上传TOKEN
    '/save_image'          => ['api/:version.Picture/saveQiniuImage',     ['method' => 'post']],                   // 保存上传至七牛图片
    '/save_activity_image' => ['api/:version.Activity/saveActivityImage', ['method' => 'post']],                   // 保存聚会图片关联信息
    '/save_coming'         => ['api/:version.Activity/saveUserComing',    ['method' => 'post']],                   // 保存参加聚会人员信息
    '/user_info/:id'       => ['api/:version.User/getUserInfo',           ['method' => 'get'], ['id' => '\d+']],   // 获取用户信息
    '/games'               => ['api/:version.Game/getGamesAll',           ['method' => 'get']],                    // 获取所有游戏列表
    '/activity_created'    => ['api/:version.Activity/getActivityCreated',['method' => 'get']],                    // 获取用户创建的
    '/activity_joined'    => ['api/:version.Activity/getActivityjoined',  ['method' => 'get']],                    // 获取用户参加的

    '/token/user'          => ['api/:version.Token/getToken',             ['method' => 'post']],                   // 获取TOKEN
    '/token/verify'        => ['api/:version.Token/verifyToken',          ['method' => 'post']],                   // 验证TOKEN
    '/token/info'          => ['api/:version.Token/saveUserInfo',         ['method' => 'post']],                   // 保存用户信息
    '/order'               => ['api/:version.Order/placeOrder',           ['method' => 'post']],                   // 下单
    '/order/:id'           => ['api/:version.Order/getDetail',            ['method' => 'get'], ['id' => '\d+']],   // 获取订单详情
    '/theme_list'          => ['api/:version.Theme/getThemeList',         ['method' => 'get']],                    // 获取主题列表
    '/bless/:id'           => ['api/:version.Bless/getBlessByCardId',     ['method' => 'get'], ['id' => '\d+']],   // 获取祝福列表
    '/card/:id'            => ['api/:version.Card/getCardById',           ['method' => 'get'], ['id' => '\d+']],   // 获取卡 前端页面
    '/card_edit/:id'       => ['api/:version.Card/editCardInfo',          ['method' => 'get'], ['id' => '\d+']],   // 获取卡 小程序编辑页面
    '/user_card'           => ['api/:version.Card/getUserCardAll',        ['method' => 'get']],                    // 获取用户请柬
    '/wx_code'             => ['api/:version.User/wxCode',                ['method' => 'get']],                    // 获取卡 小程序码
    '/vip_info'            => ['api/:version.User/getVipInfo',            ['method' => 'get']],                    // 获取会员信息
    '/cover_make'          => ['api/:version.Card/coverMakeCard',         ['method' => 'post']],                   // 覆盖制作
    '/create_card'         => ['api/:version.Card/createCard',            ['method' => 'post']],                   // 创建请柬
    '/save_card'           => ['api/:version.Card/saveCard',              ['method' => 'post']],                   // 保存请柬
    '/save_bless'          => ['api/:version.Bless/saveBless',            ['method' => 'post']],                   // 发送祝福
    '/save_voice'          => ['api/:version.Card/saveVoice',             ['method' => 'post']],                   // 保存录音
    '/join_wedding'        => ['api/:version.Bless/joinWedding',          ['method' => 'post']],                   // 请柬回执
    '/wedding_list/:id'    => ['api/:version.Bless/getWeddingList',       ['method' => 'get'], ['id' => '\d+']],   // 获取请柬回执

    '/emot/new'            => ['api/:version.Emoticon/getEmoticonNew',    ['method' => 'get']],   // 获取请柬回执
    '/emot/list'           => ['api/:version.Emoticon/getCategoryList',   ['method' => 'get']],   // 获取请柬回执
    '/emot/cat/:id'        => ['api/:version.Emoticon/getCategoryById',   ['method' => 'get'], ['id' => '\d+']],   // 获取请柬回执
    '/emot/search/:word'   => ['api/:version.Emoticon/getSearchImage',    ['method' => 'get']],   // 获取请柬回执
    '/emot/hot_search'     => ['api/:version.Emoticon/getHotSearchWord',  ['method' => 'get']],   // 获取请柬回执

    '/emot/admin'          => ['api/:version.Admin/home',                 ['method' => 'get']],   // 获取请柬回执
    '/emot/save_image'     => ['api/:version.Admin/saveimage',            ['method' => 'post']],   // 获取请柬回执
    '/emot/image'          => ['api/:version.Admin/image',                ['method' => 'get']],   // 获取请柬回执
    '/emot/update_image'   => ['api/:version.Admin/saveImageData',        ['method' => 'post']],   // 获取请柬回执



]);


Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');            // 用户id分页获取订单列表
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');                 // 获取全部订单简要信息

Route::post('api/:version/pay/pre_order',   'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify',      'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify',   'api/:version.Pay/redirectNotify');
Route::post('api/:version/pay/concurrency', 'api/:version.Pay/notifyConcurrency');

Route::group('play', function() { // 后台以amos开始
    Route::get('read_heart', 'home/Games/readHeart'); 
    Route::get('dont_die', 'home/Games/dontDie');
    Route::get('crop', 'home/Games/crop');




},['ext'=>'html']);

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