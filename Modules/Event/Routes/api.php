<?php

Route::group(["prefix"=>"v1/event","middleware"=>"AdminApiAuth"],function (){
    /***********************************数据看板***************************************/
    //数据看板
    Route::post('event/index', 'v1\AddEventController@index');
    //数据看板
    Route::get('dashboard/index', 'v1\DashboardController@index');

    //
    Route::post('updateevent/edit', 'v1\UpdateEventController@edit');

    //任务详情页面
    Route::post('updateevent/show', 'v1\UpdateEventController@show');
    //任务分配
    Route::post('updateevent/taskall', 'v1\UpdateEventController@taskAll');




});
