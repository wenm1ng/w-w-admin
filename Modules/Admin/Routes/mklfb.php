<?php

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    //ud抓取广告数据
    Route::get('udadvert/index', 'v1\UdadvertController@index');
    //详情
    Route::get('udadvert/getDetail', 'v1\UdadvertController@getDetail');
    //更新
    Route::put('udadvert/updateUdadvert', 'v1\UdadvertController@updateUdadvert');
    //获取创意分类
    Route::get('udadvert/getCategory', 'v1\UdadvertController@getCategory');
    //头条抓取广告数据
    Route::get('udadvert/onces-index', 'v1\UdadvertController@oncesIndex');
    //详情
    Route::get('udadvert/get-onces-details', 'v1\UdadvertController@getOncesDetail');
    //修改
    Route::put('udadvert/update-onces-advert', 'v1\UdadvertController@updateOncesUdadvert');
    //上传视频
    Route::post('onces/upload-video', 'v1\OncesController@uploadVideo');
    //获取视频智能封面
    Route::get('onces/get-video-cover', 'v1\OncesController@getVideoCover');
    //添加创意
    Route::post('onces/add-creative', 'v1\OncesController@addCreative');
    //获取pc机mac
    Route::get('onces/get-mac', 'v1\OncesController@getMac');
    //api拉取头条计划
    Route::get('onces/pull-advert-list', 'v1\OncesController@pullOncesAdvert');
    //刷新token
    Route::get('onces/refresh-token', 'v1\OncesController@freshToken');
    //创建自定义创意
    Route::post('onces/add-custom-creative', 'v1\OncesController@addCustomCreative');

    Route::get('onces/get-onces-component', 'v1\OncesController@getOncesComponent');


});

