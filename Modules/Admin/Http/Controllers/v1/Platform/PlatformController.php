<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-26 14:06
 */
namespace Modules\Admin\Http\Controllers\v1\Platform;

use Illuminate\Http\Request;
use Modules\Admin\Services\platform\PlatformService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class PlatformController extends BaseApiController
{
    /**
     * @desc       商品列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     * @param Request $request
     *
     * @return mixed
     */
    public function list(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'page' => 'required|integer',
            'limit' => 'required|integer',
        ], [
            'page.required' => '页数不能为空',
            'page.integer' => '页数必须为int类型',
            'limit.required' => '每页数量不能为空',
            'limit.integer' => '每页数量必须为int类型',
        ]);

        return $this->apiResponse(function()use($request){
            return (new PlatformService())->list($request->all());
        });
    }

    /**
     * @desc       商品详情
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     * @param Request $request
     *
     * @return mixed
     */
    public function info(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|integer',
        ], [
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须为int类型',
        ]);

        return $this->apiResponse(function()use($request){
            return (new PlatformService())->info($request->get('id'));
        });
    }

    /**
     * @desc       商品新增
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:21
     * @param Request $request
     *
     * @return mixed
     */
    public function add(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'platform_name' => 'required|string',
        ], [
            'platform_name.required' => '商品名称不能为空',
            'platform_name.string' => '商品名称必须为字符串',
        ]);
        return $this->apiResponse(function()use($request){
            return (new PlatformService())->add($request->all());
        });
    }

    /**
     * @desc       修改商品
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:52
     * @param Request $request
     *
     * @return mixed
     */
    public function update(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|integer',
            'platform_name' => 'required|string',
        ], [
            'id.required' => '商品id不能为空',
            'id.integer' => '商品id必须为Int类型',
            'platform_name.required' => '商品名称不能为空',
            'platform_name.string' => '商品名称必须为字符串',
        ]);
        return $this->apiResponse(function()use($request){
            return (new PlatformService())->update($request->all());
        });
    }

    /**
     * @desc       删除商品
     * @author     文明<736038880@qq.com>
     * @date       2022-07-12 15:16
     * @param Request $request
     *
     * @return mixed
     */
    public function delete(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|array',
        ], [
            'id.required' => '商品id不能为空',
            'id.array' => '商品id必须为数组',
        ]);
        return $this->apiResponse(function()use($request){
            return (new PlatformService())->delete($request->get('id'));
        });
    }

    /**
     * @desc       修改商品状态
     * @author     文明<736038880@qq.com>
     * @date       2022-07-12 15:16
     * @param Request $request
     *
     * @return mixed
     */
    public function status(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|integer',
            'status' => 'required|integer',
        ], [
            'id.required' => '商品id不能为空',
            'id.integer' => '商品id必须为Int类型',
            'status.required' => '状态不能为空',
            'status.integer' => '状态必须为Int类型',
        ]);
        return $this->apiResponse(function()use($request){
            return (new PlatformService())->status($request->all());
        });
    }
}
