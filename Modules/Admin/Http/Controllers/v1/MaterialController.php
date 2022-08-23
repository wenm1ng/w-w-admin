<?php
/*
 * @desc       素材库控制器
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 16:41
 */
namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Models\Material;
use Modules\Admin\Services\material\MaterialService;
use Modules\Common\Validator\Validator;

class MaterialController extends BaseApiController
{
    /**
     * @desc       商品列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     *
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
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->list($request->all());
        });
    }

    /**
     * @desc       商品详情
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     *
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

        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->info($request->get('id'));
        });
    }

    /**
     * @desc       商品新增
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:21
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function add(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
//            'name' => 'required|string',
            'type' => 'required|integer',
            'urls' => 'required|array',
            'tag_ids' => 'required|array',
            'category' => 'required|integer',
            'from_type' => 'required|integer',
//            'file_md5' => 'required|string',
        ], [
//            'name.required' => '素材名称不能为空',
//            'name.string' => '素材名称必须为字符串',
            'type.required' => '类型不能为空',
            'type.integer' => '类型必须为int类型',
            'urls.required' => '链接不能为空',
            'urls.array' => '链接必须为数组',
            'tag_ids.required' => '标签不能为空',
            'tag_ids.array' => '标签必须为数组',
            'category.required' => '素材类型不能为空',
            'category.integer' => '素材类型必须为int类型',
            'from_type.required' => '收集渠道不能为空',
            'from_type.integer' => '收集渠道必须为int类型',
//            'file_md5.required' => 'md5值不能为空',
//            'file_md5.string' => 'md5值必须为字符串',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->add($request->all());
        });
    }

    public function update(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|integer',
            'name' => 'required|string',
            'type' => 'required|integer',
//            'url' => 'required|url',
            'tag_ids' => 'required|array',
//            'file_md5' => 'required|string',
        ], [
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须为int类型',
            'name.required' => '素材名称不能为空',
            'name.string' => '素材名称必须为字符串',
            'type.required' => '类型不能为空',
            'type.integer' => '类型必须为int类型',
//            'url.required' => '文件链接不能为空',
//            'url.url' => '文件链接有误',
            'tag_ids.required' => '标签不能为空',
            'tag_ids.array' => '标签必须为数组',
//            'file_md5.required' => 'md5值不能为空',
//            'file_md5.string' => 'md5值必须为字符串',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->update($request->all());
        });
    }

    /**
     * @desc       删除文件
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:17
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteUrlFile(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'url' => 'required|url',
        ], [
            'url.required' => '文件链接不能为空',
            'url.url' => '文件链接有误',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->deleteUrlFile($request->get('url'));
        });
    }

    public function batchAddTags(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'ids' => 'required|array',
            'tag_ids' => 'required|array',
            'type' => 'required|integer',
        ], [
            'ids.required' => 'id不能为空',
            'ids.array' => 'id必须为数组类型',
            'tag_ids.required' => '标签id不能为空',
            'tag_ids.array' => '标签id必须为数组类型',
            'type.required' => '类型不能为空',
            'type.integer' => '类型必须为int类型',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->batchAddTags($request->all());
        });
    }

    /**
     * @desc       批量累加下载量
     * @author     文明<736038880@qq.com>
     * @date       2022-08-22 13:23
     * @param Request $request
     *
     * @return mixed
     */
    public function batchAddDownloadNum(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'ids' => 'required|array',
        ], [
            'ids.required' => 'id不能为空',
            'ids.array' => 'id必须为数组类型',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->batchAddDownloadNum($request->all());
        });
    }

    /**
     * @desc       删除
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:32
     * @param Request $request
     *
     * @return mixed
     */
    public function delete(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|array',
        ], [
            'id.required' => 'id不能为空',
            'id.array' => 'id必须为数组',
        ]);

        return $this->apiResponse(function () use ($request) {
            return (new MaterialService())->delete($request->get('id'));
        });
    }

    /**
     * @desc       修改状态
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:34
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
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须为Int类型',
            'status.required' => '状态不能为空',
            'status.integer' => '状态必须为Int类型',
        ]);
        return $this->apiResponse(function()use($request){
            return (new MaterialService())->status($request->all());
        });
    }
}
