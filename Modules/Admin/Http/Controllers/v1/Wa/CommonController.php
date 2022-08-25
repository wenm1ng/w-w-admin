<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-24 10:00
 */
namespace Modules\Admin\Http\Controllers\v1\Wa;

use Illuminate\Http\Request;
use Modules\Admin\Services\wa\CommonService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Http\Controllers\v1\BaseApiController;

class CommonController extends BaseApiController
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
    public function getVersionList(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new CommonService())->getVersionList($request->all());
        });
    }

    /**
     * @desc       职业列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-24 17:07
     * @param Request $request
     *
     * @return mixed
     */
    public function getOcList(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new CommonService())->getOcList($request->all());
        });
    }

    /**
     * @desc       tab列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-24 17:44
     * @param Request $request
     *
     * @return mixed
     */
    public function getTabList(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new CommonService())->getTabList($request->all());
        });
    }

}
