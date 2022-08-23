<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-08 9:49
 */
namespace Modules\Admin\Http\Controllers\v1\Platform;

use Illuminate\Http\Request;
use Modules\Admin\Services\platform\AuthService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class AuthController extends BaseApiController
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
    public function getAuthUrl(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new AuthService())->getAuthUrl();
        });
    }

    /**
     * @desc       授权回调
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:48
     * @param Request $request
     *
     * @return mixed
     */
    public function back(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new AuthService())->back($request->all());
        });
    }

    public function getAuthList(Request $request){
        $params = $request->all();
        Validator::check($params, [
            'page' => 'required|integer',
            'limit' => 'required|integer',
            'platform_id' => 'required|integer',
        ], [
            'page.required' => '页数不能为空',
            'page.integer' => '页数必须为int类型',
            'limit.required' => '每页数量不能为空',
            'limit.integer' => '每页数量必须为int类型',
            'platform_id.required' => '平台id不能为空',
            'platform_id.integer' => '平台id必须为int类型',
        ]);

        return $this->apiResponse(function () use ($request) {
            return (new AuthService())->getAuthList($request->all());
        });
    }
}
