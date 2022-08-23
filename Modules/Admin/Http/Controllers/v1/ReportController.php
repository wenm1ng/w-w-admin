<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-16 17:24
 */
namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Services\report\ReportService;
use Modules\Common\Validator\Validator;

class ReportController extends BaseApiController
{
    /**
     * @desc       计划报表列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function planList(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->planList($request->all());
        });
    }

    /**
     * @desc       汇总报表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-16 17:57
     * @param Request $request
     *
     * @return mixed
     */
    public function accountSummary(Request $request)
    {
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->accountSummary($request->all());
        });
    }

    /**
     * @desc       计划页面搜索框
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 17:17
     * @param Request $request
     *
     * @return mixed
     */
    public function planDropDown(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->planDropDown($request->all());
        });
    }

    public function getPushList(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->getPushList($request->all());
        });
    }

    /**
     * @desc       获取统计列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-19 14:24
     * @param Request $request
     *
     * @return mixed
     */
    public function getStatList(Request $request){
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->getStatList($request->all());
        });
    }

    /**
     * @desc       获取团队列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-22 10:30
     * @param Request $request
     *
     * @return mixed
     */
    public function getTeamList(Request $request){
        return $this->apiResponse(function (){
            return (new ReportService())->getTeamList();
        });
    }


}
