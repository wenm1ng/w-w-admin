<?php

Route::group(["prefix"=>"v1/bigdata","middleware"=>"AdminApiAuth"],function (){
    /***********************************数据看板***************************************/
    //数据看板
    Route::get('dashboard/index', 'v1\DashboardController@index');

    Route::post('report/index', 'v1\ReportController@index');
    Route::get('report/summary', 'v1\ReportController@Summary');
    Route::get('report/dropdown', 'v1\ReportController@dropDown');
    Route::post('report/alertsetting', 'v1\ReportController@AlertSetting');
    Route::post('report/settinglist', 'v1\ReportController@SettingList');//callQuery
    Route::post('sendmessage/callquery', 'v1\SendMessageController@callQuery');//callQuery
    Route::post('sendmessage/newquery', 'v1\SendMessageController@newEarly');//SendMessagePlanService
    Route::post('sendmessage/anewquery', 'v1\SendMessageController@AnewEarly');//SendMessagePlanService

    Route::get('account/index', 'v1\AccountController@index');









});
