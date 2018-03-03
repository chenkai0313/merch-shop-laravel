<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    #无需身份验证
    $api->group(['namespace' => 'Modules\Wx\Http\Controllers', 'prefix' => 'wx', 'middleware' => ['session_start']], function ($api) {
        $api->post('user-init', 'UserController@userInit');
    });

    $api->group(['namespace' => 'Modules\Wx\Http\Controllers', 'prefix' => 'wx', 'middleware' => ['random_session', 'session_start']], function ($api) {
        #敏感字过滤
        $api->get('filter-word', 'WxController@filterWord');
        #回调地址
        $api->post('pay-notify', 'WxController@notify');
        #支付
        $api->post('pay-money-config', 'WxController@getPayConfig');
        #获取二维码
        $api->get('get-qrcode-config', 'WxController@getQrcodeConfig');
        #首页
        $api->get('protal-page', 'IndexController@protalData');
        #发现
        $api->get('find-page', 'IndexController@findData');
        #发现商品详情
        $api->get('find-page-detail', 'IndexController@findDataDeatil');
        #点赞
        $api->post('like-add', 'IndexController@likeAdd');
        $api->post('like-delete', 'IndexController@likeDelete');
        #评论的添加
        $api->post('comment-add', 'IndexController@commentAdd');
        $api->post('comment-delete', 'IndexController@commentDelete');
        #订单
        $api->post('order-add', 'IndexController@orderAdd');
        $api->post('order-edit', 'IndexController@orderEdit');
        $api->get('order-detail', 'IndexController@orderDetail');
        #个人订单
        $api->get('order-list', 'IndexController@orderList');
    });
});