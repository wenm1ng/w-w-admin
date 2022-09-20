<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 17:58
 */
namespace Modules\Admin\Http\Controllers\v1\Wa;

use Illuminate\Http\Request;
use Modules\Admin\Services\wa\RankService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class RankController extends BaseApiController
{
    /**
     * @desc       排行榜列表
     * @author     文明<736038880@qq.com>
     * @date       2022-09-19 13:19
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
            'week' => 'required|array',
        ], [
            'page.required' => 'page不能为空',
            'limit.required' => 'limit不能为空',
            'week.required' => 'week不能为空',
            'week.array' => 'week必须为数组',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new RankService())->list($request->all());
        });
    }

    /**
     * @desc       回答列表
     * @author     文明<736038880@qq.com>
     * @date       2022-09-19 15:00
     * @param Request $request
     *
     * @return mixed
     */
    public function getAnswerList(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'user_id' => 'required',
            'create_at' => 'required',
        ], [
            'user_id.required' => 'user_id不能为空',
            'create_at.required' => 'create_at不能为空',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new RankService())->getAnswerList($request->all());
        });
    }

    /**
     * @desc       删除回答
     * @author     文明<736038880@qq.com>
     * @date       2022-09-20 10:28
     * @param Request $request
     *
     * @return mixed
     */
    public function delAnswer(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|array',
        ], [
            'id.required' => 'id不能为空',
            'id.array' => 'id必须为数组',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new RankService())->delAnswer($request->input('id'));
        });
    }

    /**
     * @desc       修改回答状态
     * @author     文明<736038880@qq.com>
     * @date       2022-09-20 11:11
     * @param Request $request
     *
     * @return mixed
     */
    public function answerStatus(Request $request){
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
            return (new RankService())->answerStatus($request->all());
        });
    }
}
