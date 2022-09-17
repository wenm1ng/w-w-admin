<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 15:39
 */
namespace Modules\Admin\Http\Controllers\v1\Wa;

use Illuminate\Http\Request;
use Modules\Admin\Services\wa\CommentService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class CommentController extends BaseApiController
{
    /**
     * @desc       wa列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 17:17
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function list(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'page' => 'required',
            'limit' => 'required',
        ], [
            'page.required' => 'page不能为空',
            'limit.integer' => 'limit不能为空',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new CommentService())->list($request->all());
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
            'id.required' => 'id不能为空',
            'id.array' => 'id必须为数组类型',
        ]);
        return $this->apiResponse(function()use($request){
            return (new CommentService())->delete($request->input('id'));
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
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须为Int类型',
            'status.required' => '状态不能为空',
            'status.integer' => '状态必须为Int类型',
        ]);
        return $this->apiResponse(function()use($request){
            return (new CommentService())->status($request->all());
        });
    }
}
