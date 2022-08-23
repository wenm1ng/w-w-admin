<?php
/*
 * @desc       商品管理路由
 * @author     文明<736038880@qq.com>
 * @date       2022-07-08 16:07
 */
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    //新增商品
    Route::post('product/add', 'v1\ProductController@add');
    //商品列表
    Route::get('product/list', 'v1\ProductController@list');
    //商品详情
    Route::get('product/info', 'v1\ProductController@info');
    //商品编辑
    Route::put('product/update', 'v1\ProductController@update');
    //商品删除
    Route::put('product/delete', 'v1\ProductController@delete');
    //修改商品状态
    Route::put('product/status', 'v1\ProductController@status');

});

