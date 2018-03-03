<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    #无需身份验证
    $api->group(['namespace' => 'Modules\Backend\Http\Controllers', 'prefix' => 'backend', 'middleware' => ['session_start']], function ($api) {
        // 验证码
        $api->get('code/{tmp}', 'AdminController@qrcode');
        #商家配置
        $api->get('config-detail', 'ConfigController@configDetail');
    });
    #需要身份验证
    $api->group(['namespace' => 'Modules\Backend\Http\Controllers', 'prefix' => 'backend', 'middleware' => ['jwt-admin', 'log-admin', 'session_start']], function ($api) {
        #账户管理
        $api->post('admin-login', 'AdminController@adminLogin');
        $api->get('admin-detail', 'AdminController@adminDetail');
        $api->post('admin-edit', 'AdminController@adminEdit');
        $api->post('admin-delete', 'AdminController@adminDelete');
        $api->post('admin-change-pwd', 'AdminController@adminChangePassword');
        #注册
        $api->post('admin-add', 'AdminController@adminAdd');
        #操作日志管理
        $api->get('log-list', 'LogController@logList');
        $api->get('log-detail', 'LogController@logDetail');
        #文件上传
        $api->post('file-upload-all', 'FileUploadController@fileUpLoadAll');    //多文件上传
        $api->post('file-upload', 'FileUploadController@fileUpLoad');    //单文件上传
        $api->post('qiniu-upload', 'FileUploadController@qiniuUpload'); //七牛单图
        $api->post('qiniu-upload-all', 'FileUploadController@qiniuUploadAll'); //七牛多图
        #轮播图
        $api->post('adv-add', 'AdvController@advAdd');
        $api->post('adv-edit', 'AdvController@advEdit');
        $api->post('adv-delete', 'AdvController@advDelete');
        $api->get('adv-detail', 'AdvController@advDetail');
        $api->get('adv-list', 'AdvController@advList');
        #商品
        $api->post('product-add', 'ProductController@productAdd');
        $api->post('product-edit', 'ProductController@productEdit');
        $api->post('product-delete', 'ProductController@productDelete');
        $api->get('product-detail', 'ProductController@productDetail');
        $api->get('product-list', 'ProductController@productList');
        #点赞
        $api->get('like-list', 'LikeController@likeList');
        #评论
        $api->post('comment-add', 'CommentController@commentAdd');
        $api->post('comment-edit', 'CommentController@commentEdit');
        $api->post('comment-delete', 'CommentController@commentDelete');
        $api->get('comment-detail', 'CommentController@commentDetail');
        $api->get('comment-list', 'CommentController@commentList');//针对商品评论列表
        $api->get('comment-list-all', 'CommentController@commentListAll');//所有评论列表
        #用户
        $api->post('user-add', 'UserController@userAdd');
        $api->post('user-edit', 'UserController@userEdit');
        $api->post('user-delete', 'UserController@userDelete');
        $api->get('user-detail', 'UserController@userDetail');
        $api->get('user-list', 'UserController@userList');
        $api->get('user-list-all', 'UserController@userListAll');
        #商家配置
        $api->post('config-edit', 'ConfigController@configEdit');
        #订单
        $api->post('order-add', 'OrderController@orderAdd');
        $api->post('order-edit', 'OrderController@orderEdit');
        $api->post('order-delete', 'OrderController@orderDelete');
        $api->get('order-detail', 'OrderController@orderDetail');
        $api->get('order-list', 'OrderController@orderList');
    });


});

