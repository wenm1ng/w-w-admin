<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-23 15:57
 */
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    /***********************************wa相关接口************************************************/
    //wa列表
    Route::get('wa/list', 'v1\Wa\WaController@list');
    //wa详情
    Route::get('wa/info', 'v1\Wa\WaController@info');
    //新增wa
    Route::post('wa/add', 'v1\Wa\WaController@add');
    //修改wa
    Route::post('wa/update', 'v1\Wa\WaController@update');
    //修改wa状态
    Route::post('wa/status', 'v1\Wa\WaController@status');

    /***********************************评论相关接口************************************************/
    //评论列表
    Route::get('wa/comment/list', 'v1\Wa\CommentController@list');
    //删除评论
    Route::post('wa/comment/del', 'v1\Wa\CommentController@delete');
    //修改评论状态
    Route::post('wa/comment/status', 'v1\Wa\CommentController@status');
    /***********************************排行榜相关接口************************************************/
    Route::get('wa/comment/rank', 'v1\Wa\RankController@list');

    /***********************************公共数据接口************************************************/
    //版本列表
    Route::get('wa/version/list', 'v1\Wa\CommonController@getVersionList');
    //职业列表
    Route::get('wa/oc/list', 'v1\Wa\CommonController@getOcList');
    //tab列表
    Route::get('wa/tab/list', 'v1\Wa\CommonController@getTabList');

});
