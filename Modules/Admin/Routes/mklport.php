<?php

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    //ud抓取广告数据
    Route::get('adportlist/index', 'v1\AdPortListController@index');
    Route::get('adportlist/getreportlist', 'v1\AdPortListController@getPlanReportList');//
    Route::get('adportlist/getuserreportlist', 'v1\AdPortListController@getUserReportList');//
    Route::get('adportlist/getusersmake', 'v1\AdPortListController@getUsersMake');// getUsersMake
    Route::get('adportlist/cs', 'v1\AdPortListController@cs');
    Route::get('adportlist/accountlist', 'v1\AdPortListController@accountlist');//获取纵横组织下资产账户列表
    Route::get('adportlist/getPlanReportList', 'v1\AdPortListController@getPlanReportList');//SendMessagePlanService
//    Route::get('adportlist/getPlanReportList', 'v1\AdPortListController@getPlanReportList');//SendMessagePlanService
    Route::get('adportlist/test', 'v1\AdPortListController@test');//getCampaign
    Route::get('adportlist/getcampaign', 'v1\AdPortListController@getCampaign');//getCampaign  createCampaign
    Route::post('adportlist/createcampaign', 'v1\AdPortListController@createCampaign');//getCampaign  createCampaign


    Route::get('accountstatic/zts_statistics', 'v1\AccountStatisticController@zts_statistics');//getCampaign  zts_redis

    Route::get('accountstatic/zts_redis', 'v1\AccountStatisticController@zts_redis');//test
    Route::get('accountstatic/test', 'v1\AccountStatisticController@test');//test

//    lv_account_statistics
});

