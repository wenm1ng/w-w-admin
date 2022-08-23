<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-23 15:58
 */
namespace Modules\Admin\Http\Controllers\v1\Wa;

use Illuminate\Http\Request;
use Modules\Admin\Services\wa\WaService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class WaController extends BaseApiController
{
    /**
     * @desc       wa列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 17:17
     * @param Request $request
     *
     * @return mixed
     */
    public function list(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new WaService())->list($request->all());
        });
    }

    /**
     * @desc       wa详情
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 18:15
     * @param Request $request
     *
     * @return mixed
     */
    public function info(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new WaService())->info($request->get('id'));
        });
    }

    /**
     * @desc       新增wa
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 18:15
     * @param Request $request
     *
     * @return mixed
     */
    public function add(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new WaService())->add($request->all());
        });
    }
}
