<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-16 17:16
 */
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    /***********************************计划报表接口************************************************/
    //计划报表列表
    Route::post('report/plan/list', 'v1\ReportController@planList');
    //账号汇总报表
    Route::get('report/account/summary', 'v1\ReportController@accountSummary');
    //素材搜索列表
    Route::get('report/plan/drop-down', 'v1\ReportController@planDropDown');

    /***********************************平台统计************************************************/
    Route::get('stat/list', 'v1\ReportController@getStatList');
    //团队列表
    Route::get('team/list', 'v1\ReportController@getTeamList');

    /***********************************素材管理************************************************/
    //新增素材
    Route::post('material/add', 'v1\MaterialController@add');
    //修改素材
    Route::post('material/update', 'v1\MaterialController@update');
    //素材详情
    Route::get('material/info', 'v1\MaterialController@info');
    //素材列表
    Route::post('material/list', 'v1\MaterialController@list');
    //素材删除
    Route::post('material/delete', 'v1\MaterialController@delete');
    //文件删除
    Route::post('material/delete-file', 'v1\MaterialController@deleteUrlFile');
    //修改素材状态
    Route::post('material/status', 'v1\MaterialController@status');
    //批量修改素材标签
    Route::post('material/update-tags', 'v1\MaterialController@batchAddTags');
    //批量累加下载量
    Route::post('material/add-download-num', 'v1\MaterialController@batchAddDownloadNum');


    /***********************************素材管理************************************************/
    //新增平台
    Route::post('platform/add', 'v1\Platform\PlatformController@add');
    //修改平台
    Route::post('platform/update', 'v1\Platform\PlatformController@update');
    //平台详情
    Route::get('platform/info', 'v1\Platform\PlatformController@info');
    //平台列表
    Route::get('platform/list', 'v1\Platform\PlatformController@list');
    //平台删除
    Route::post('platform/delete', 'v1\Platform\PlatformController@delete');
    //修改平台状态
    Route::post('platform/status', 'v1\Platform\PlatformController@status');

    /*****************************************首页相关*******************************************/
    Route::get('report/get-push-list', 'v1\ReportController@getPushList');
    /*****************************************授权相关*******************************************/
    //获取授权url
    Route::get('auth/get-auth-url', 'v1\Platform\AuthController@getAuthUrl');
    //获取授权列表\
    Route::get('auth/list', 'v1\Platform\AuthController@getAuthList');
});

Route::group(["prefix"=>"v1/admin"],function (){
    //授权回调
    Route::any('back', 'v1\Platform\AuthController@back');
    /***********************************内部调用接口************************************************/
    //保存新素材信息
    Route::any('inside/save-video-info', 'v1\InsideController@saveVideoInfo');
    //导入旧素材
    Route::post('inside/import-old-material', 'v1\InsideController@importOldMaterial');
    //测试接口
    Route::any('test', 'v1\TestController@test');

});
