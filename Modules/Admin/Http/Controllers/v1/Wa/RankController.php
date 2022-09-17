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
}
