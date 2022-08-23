<?php
/**
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-08 14:26
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Services\product\ProductService;
use Modules\Common\Validator\Validator;

class ProductController extends BaseApiController
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
        return $this->apiResponse(function()use($request){
            return (new ProductService())->list($request->all());
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
            return (new ProductService())->info($request->get('id'));
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
            'product_name' => 'required|string',
            'brand_id' => 'required|integer',
            'product_type' => 'required|string',
            'product_url' => 'required|active_url',
            'price' => 'required|numeric',
            'image_url' => 'required|active_url',
        ], [
            'product_name.required' => '商品名称不能为空',
            'product_name.string' => '商品名称必须为字符串',
            'brand_id.required' => '品牌不能为空',
            'brand_id.integer' => '品牌必须为int类型',
            'product_type.required' => '商品规格不能为空',
            'product_type.string' => '商品规格必须为字符串',
            'product_url.required' => '商品链接不能为空',
            'product_url.active_url' => '请输入正确的商品链接',
            'price.required' => '商品价格不能为空',
            'price.numeric' => '请输入正确的商品价格',
            'image_url.required' => '商品图片不能为空',
            'image_url.active_url' => '请上传正确的商品图片',
        ]);
        return $this->apiResponse(function()use($request){
            return (new ProductService())->add($request->all());
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
            'product_name' => 'required|string',
            'brand_id' => 'required|integer',
            'product_type' => 'required|string',
            'product_url' => 'required|active_url',
            'price' => 'required|numeric',
            'image_url' => 'required|active_url',
        ], [
            'id.required' => '商品id不能为空',
            'id.integer' => '商品id必须为Int类型',
            'product_name.required' => '商品名称不能为空',
            'product_name.string' => '商品名称必须为字符串',
            'brand_id.required' => '品牌不能为空',
            'brand_id.integer' => '品牌必须为int类型',
            'product_type.required' => '商品规格不能为空',
            'product_type.string' => '商品规格必须为字符串',
            'product_url.required' => '商品链接不能为空',
            'product_url.active_url' => '请输入正确的商品链接',
            'price.required' => '商品价格不能为空',
            'price.numeric' => '请输入正确的商品价格',
            'image_url.required' => '商品图片不能为空',
            'image_url.active_url' => '请上传正确的商品图片',
        ]);
        return $this->apiResponse(function()use($request){
            return (new ProductService())->update($request->all());
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
            'id.array' => '商品id必须为数组类型',
        ]);
        return $this->apiResponse(function()use($request){
            return (new ProductService())->delete($request->get('id'));
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
            return (new ProductService())->status($request->all());
        });
    }
}
