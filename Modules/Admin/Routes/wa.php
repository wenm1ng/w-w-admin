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
});
