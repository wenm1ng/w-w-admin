<?php
// +----------------------------------------------------------------------
// | Name: 咪乐多管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: 西安咪乐多软件 <997786358@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name 用户经验值规则控制器
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/28 14:33
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Http\Requests\CommonSortRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\empiricalValue\EmpiricalValueService;

class EmpiricalValueController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  name String 规则名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new EmpiricalValueService())->index($request->only([
            'page',
            'limit',
            'name',
            'status',
            'created_at',
            'updated_at'
        ]));
    }
    /**
     * @name 添加
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 8:42
     * @method  POST
     * @param  name String 规则名称
     * @param  content String 规则描述
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sort Int 排序
     * @param  value Int 获取经验值
     * @param  restrict_value Int 限制经验值，以天为单位，0表示没有限制
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new EmpiricalValueService())->store($request->only([
            'name',
            'content',
            'status',
            'sort',
            'value',
            'restrict_value',
        ]));
    }
    /**
     * @name 编辑页面
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int 经验值规则ID
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new EmpiricalValueService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 经验值规则ID
     * @param  name String 规则名称
     * @param  content String 规则描述
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sort Int 排序
     * @param  value Int 获取经验值
     * @param  restrict_value Int 限制经验值，以天为单位，0表示没有限制
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new EmpiricalValueService())->update($request->get('id'),$request->only([
            'name',
            'content',
            'status',
            'sort',
            'value',
            'restrict_value',
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 经验值规则ID
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new EmpiricalValueService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 排序
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 经验值规则ID
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new EmpiricalValueService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 经验值规则ID
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new EmpiricalValueService())->cDestroy($request->get('id'));
    }
}
