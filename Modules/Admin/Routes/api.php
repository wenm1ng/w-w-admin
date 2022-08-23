<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    //登录
    Route::post('login/login', 'v1\LoginController@login');
    // 获取管理员信息
    Route::get('admin/info', 'v1\IndexController@info');
    /***********************************首页***************************************/
    //刷新token
    Route::put('index/refreshToken', 'v1\IndexController@refreshToken');
    //退出登录
    Route::delete('index/logout', 'v1\IndexController@logout');
    //清除缓存
    Route::delete('index/outCache', 'v1\IndexController@outCache');
    //修改密码
    Route::put('index/upadtePwdView', 'v1\IndexController@upadtePwdView');
    //获取模块
    Route::get('index/getModel', 'v1\IndexController@getModel');
    //获取左侧栏
    Route::get('index/getMenu', 'v1\IndexController@getMenu');

    //单图上传
    Route::post('upload/fileImage', 'v1\UploadController@fileImage');

    //图片列表
    Route::get('upload/getImageList', 'v1\UploadController@getImageList');
    //获取平台信息
    Route::get('index/getMain', 'v1\IndexController@getMain');
    // 获取地区数据
    Route::get('index/getAreaData', 'v1\IndexController@getAreaData');
    // 转换编辑器内容
    Route::post('index/setContent', 'v1\IndexController@setContent');
    /***********************************管理员列表***************************************/
    //列表数据
    Route::get('admin/index', 'v1\AdminController@index');
    //获取权限组
    Route::get('admin/getGroupList', 'v1\AdminController@getGroupList');
    //获取项目列表
    Route::get('admin/getProjectList', 'v1\AdminController@getProjectList');
    //添加
    Route::post('admin/store', 'v1\AdminController@store');
    //编辑页面
    Route::get('admin/edit', 'v1\AdminController@edit');
    //编辑提交
    Route::put('admin/update', 'v1\AdminController@update');
    //调整状态
    Route::put('admin/status', 'v1\AdminController@status');
    //初始化密码
    Route::put('admin/updatePwd', 'v1\AdminController@updatePwd');
    //导入登录用户
    Route::post('admin/import', 'v1\AdminController@import');
    //清除mac唯一id
    Route::delete('admin/del-unique-id', 'v1\AdminController@delUniqueId');

    /***********************************权限组列表***************************************/
    //列表数据
    Route::get('group/index', 'v1\GroupController@index');
    //添加
    Route::post('group/store', 'v1\GroupController@store');
    //编辑页面
    Route::get('group/edit', 'v1\GroupController@edit');
    //编辑提交
    Route::put('group/update', 'v1\GroupController@update');
    //调整状态
    Route::put('group/status', 'v1\GroupController@status');
    //分配权限
    Route::get('group/access', 'v1\GroupController@access');
    //分配权限提交
    Route::put('group/accessUpdate', 'v1\GroupController@accessUpdate');

    /***********************************菜单管理***************************************/
    //列表数据
    Route::get('rule/index', 'v1\RuleController@index');
    //添加
    Route::post('rule/store', 'v1\RuleController@store');
    // 添加子级返回父级id
    Route::get('rule/pidArr', 'v1\RuleController@pidArr');
    //编辑页面
    Route::get('rule/edit', 'v1\RuleController@edit');
    //编辑提交
    Route::put('rule/update', 'v1\RuleController@update');
    //菜单状态
    Route::put('rule/status', 'v1\RuleController@status');
    //是否验证权限
    Route::put('rule/open', 'v1\RuleController@open');
    // 固定面板
    Route::put('rule/affix', 'v1\RuleController@affix');
    //排序
    Route::put('rule/sorts', 'v1\RuleController@sorts');
    //删除
    Route::delete('rule/cDestroy', 'v1\RuleController@cDestroy');

    /***********************************系统配置***************************************/
    //系统配置
    Route::get('config/index', 'v1\ConfigController@index');
    //提交
    Route::put('config/update', 'v1\ConfigController@update');
    /***********************************地区列表***************************************/
    //地区列表
    Route::get('area/index', 'v1\AreaController@index');
    //添加
    Route::post('area/store', 'v1\AreaController@store');
    //编辑页面
    Route::get('area/edit', 'v1\AreaController@edit');
    //编辑提交
    Route::put('area/update', 'v1\AreaController@update');
    //状态
    Route::put('area/status', 'v1\AreaController@status');
    //排序
    Route::put('area/sorts', 'v1\AreaController@sorts');
    //删除
    Route::delete('area/cDestroy', 'v1\AreaController@cDestroy');
    //导入服务器数据
    Route::get('area/importData', 'v1\AreaController@importData');
    // 写入地区缓存
    Route::post('area/setAreaData', 'v1\AreaController@setAreaData');
    /***********************************操作日志***************************************/
    //操作日志
    Route::get('operationLog/index', 'v1\OperationLogController@index');
    //删除
    Route::delete('operationLog/cDestroy', 'v1\OperationLogController@cDestroy');
    //批量删除
    Route::delete('operationLog/cDestroyAll', 'v1\OperationLogController@cDestroyAll');
    /***********************************数据库管理***************************************/
    //数据表管理
    Route::get('dataBase/index','v1\DataBaseController@index');
    // 表详情
    Route::get('dataBase/tableData', 'v1\DataBaseController@tableData');
    // 备份表
    Route::post('dataBase/backUp', 'v1\DataBaseController@backUp');
    // 备份列表
    Route::get('dataBase/restoreData', 'v1\DataBaseController@restoreData');
    // 查询文件详情
    Route::get('dataBase/getFiles', 'v1\DataBaseController@getFiles');
    // 删除
    Route::delete('dataBase/delSqlFiles', 'v1\DataBaseController@delSqlFiles');
    /***********************************数据看板***************************************/
    //数据看板
    Route::get('index/dashboard','v1\IndexController@dashboard');
    // 接口请求图表数据
    Route::get('index/getLogCountList','v1\IndexController@getLogCountList');
    /***********************************项目管理***************************************/
    //项目管理
    Route::get('project/index', 'v1\ProjectController@index');
    //添加
    Route::post('project/store', 'v1\ProjectController@store');
    //编辑页面
    Route::get('project/edit', 'v1\ProjectController@edit');
    //编辑提交
    Route::put('project/update', 'v1\ProjectController@update');
    //调整状态
    Route::put('project/status', 'v1\ProjectController@status');
    /***********************************会员管理***************************************/
    //会员管理
    Route::get('user/index', 'v1\UserController@index');
    //添加
    Route::post('user/store', 'v1\UserController@store');
    //编辑页面
    Route::get('user/edit', 'v1\UserController@edit');
    //编辑提交
    Route::put('user/update', 'v1\UserController@update');
    //调整状态
    Route::put('user/status', 'v1\UserController@status');
    //初始化密码
    Route::put('user/updatePwd', 'v1\UserController@updatePwd');

    /***********************************分类管理**************************************/

    //列表
    Route::get('category/index', 'v1\CategoryController@index');
    //添加
    Route::post('category/store', 'v1\CategoryController@store');
    //编辑页面
    Route::get('category/edit', 'v1\CategoryController@edit');
    //编辑提交
    Route::put('category/update', 'v1\CategoryController@update');
    //调整状态
    Route::put('category/status', 'v1\CategoryController@status');
    // 删除
    Route::delete('category/del', 'v1\CategoryController@cDestroy');
    /***********************************部门管理**************************************/

    //列表
    Route::get('department/index', 'v1\DepartmentController@index');
    //部门人员信息
    Route::get('department/departUser', 'v1\DepartmentController@departUser');
    //添加
    Route::post('department/store', 'v1\DepartmentController@store');
    //编辑页面
    Route::get('department/edit', 'v1\DepartmentController@edit');
    //编辑提交
    Route::put('department/update', 'v1\DepartmentController@update');

    Route::delete('department/del', 'v1\DepartmentController@cDestroy');

   /***********************************第三方用户管理**************************************/

    //列表
    Route::get('secuser/index', 'v1\SecuserController@index');
    //添加
    Route::post('secuser/store', 'v1\SecuserController@store');
    //编辑页面
    Route::get('secuser/edit', 'v1\SecuserController@edit');
    //编辑提交
    Route::put('secuser/update', 'v1\SecuserController@update');
    //调整状态
    Route::put('secuser/status', 'v1\SecuserController@status');
    // 删除
    Route::delete('secuser/del', 'v1\SecuserController@cDestroy');

    /***********************************品牌管理**************************************/

    Route::any('brand/test', 'v1\BrandController@test');

    //列表
    Route::get('brand/index', 'v1\BrandController@index');
    //添加
    Route::post('brand/store', 'v1\BrandController@store');
    //编辑页面
    Route::get('brand/edit', 'v1\BrandController@edit');
    //编辑提交
    Route::put('brand/update', 'v1\BrandController@update');
    //调整状态
    Route::put('brand/status', 'v1\BrandController@status');
    // 删除
    Route::delete('brand/del', 'v1\BrandController@cDestroy');

    // 分类选项
    Route::get('brand/getcate', 'v1\BrandController@getCate');


    /***********************************标签管理**************************************/

    //列表
    Route::get('tag/index', 'v1\TagController@index');
    //添加
    Route::post('tag/store', 'v1\TagController@store');
    //编辑页面
    Route::get('tag/edit', 'v1\TagController@edit');
    //编辑提交
    Route::put('tag/update', 'v1\TagController@update');
    // 删除
    Route::delete('tag/del', 'v1\TagController@cDestroy');

    /***********************************团队管理**************************************/

    //团队管理
    Route::get('team/index', 'v1\TeamController@index');
    //详情
    Route::get('team/detail', 'v1\TeamController@detail');
    //添加
    Route::post('team/addTeams', 'v1\TeamController@addTeams');
    //修改
    Route::put('team/updateTeam', 'v1\TeamController@updateTeam');
    //删除
    Route::delete('team/delTeam', 'v1\TeamController@delTeam');


    /***********************************店铺管理***************************************/
    //店铺管理
    Route::get('store/index', 'v1\StoreController@index');
    //添加
    Route::post('store/store', 'v1\StoreController@store');
    //编辑页面
    Route::get('store/edit', 'v1\StoreController@edit');
    //编辑提交
    Route::put('store/update', 'v1\StoreController@update');
    //调整状态
    Route::put('store/status', 'v1\StoreController@status');
    //删除店铺
    Route::delete('store/destroy', 'v1\StoreController@destroy');

    // 广告管理
    Route::resource('advert', 'v1\AdvertController')->parameter('advert', 'id');
    // 广告组
    Route::resource('advertGroup', 'v1\AdvertGroupController')->parameter('advertGroup', 'id');
    // 广告计划
    Route::resource('advertCampaign', 'v1\AdvertCampaignController')->parameter('advertCampaign', 'id');
    // 广告创意
    Route::resource('advertCreative', 'v1\AdvertCreativeController')->parameter('advertCreative', 'id');

    /***********************************计划监控***************************************/
    //计划监控 数据展示
    Route::get('monitor/index', 'v1\MonitorController@index');
    //账号下拉框数据
    Route::post('monitor/dropdown', 'v1\MonitorController@dropdown');
    //计划组下拉框数据
    Route::post('monitor/dropdownplangroup', 'v1\MonitorController@dropDownPlanGroup');
    //计划 下拉框数据
    Route::post('monitor/dropdownplan', 'v1\MonitorController@dropDownPlan');
    //规则列表
    Route::get('monitor/rules', 'v1\MonitorController@rules');
    //规则修改
    Route::get('monitor/rulesedit', 'v1\MonitorController@rulesedit');
    //规则提交
    Route::put('monitor/rulesupdate', 'v1\MonitorController@rulesupdate');
    //规则提交
    Route::put('monitor/rulesstatus', 'v1\MonitorController@rulesstatus');
    //开关单独修改
    Route::put('monitor/ruleswitch', 'v1\MonitorController@rulesSwitch');
    //删除规则
    Route::delete('monitor/rulesdestroy', 'v1\MonitorController@rulesdestroy');
    //添加规则  rulesadd
    Route::post('monitor/rulesadd', 'v1\MonitorController@rulesadd');

    //平台下拉框接口
    Route::post('monitor/platlist', 'v1\MonitorController@platList');

});

