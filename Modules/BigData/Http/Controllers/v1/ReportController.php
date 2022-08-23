<?php

namespace Modules\BigData\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\BigData\Http\Requests\CommonPageRequest;
use Modules\BigData\Http\Requests\CommonAlertSetting;
use Modules\BigData\Services\BaseApiService;
use Modules\BigData\Services\report\ReportService;
use Modules\Common\Validator\Validator;
class ReportController extends BaseApiController
{
    /**
     * @Notes: 列表查詢
     * @Author: 1900
     * @Date: 2022/8/15 16:19
     * @Interface index
     * @param CommonPageRequest $request
     * @return \Modules\BigData\Services\report\JSON
     */
    public function index(CommonPageRequest $request)
    {
        return (new ReportService())->index($request->only([
            'page',
            'limit',
            'name',
            'str',
            'plat_id',
            'media_id',
            'created_at',
            'updated_at'
        ]));
    }

    /**
     * @Notes: 报警设置
     * @Author: 1900
     * @Date: 2022/8/15 16:19
     * @Interface AlertSetting
     * @param CommonAlertSetting $request
     * @return \Modules\BigData\Services\report\JSON
     */
    public function AlertSetting(CommonAlertSetting $request){
        return (new ReportService())->AlertSetting($request->only([
            'name',
            'field_name',
            'values',
            'created_at',
            'updated_at'
        ]));
    }

    /**
     * @Notes: 报警展示
     * @Author: 1900
     * @Date: 2022/8/15 16:23
     * @Interface SettingList
     * @return \Modules\BigData\Services\report\JSON
     */
    public function SettingList()
    {
        return (new ReportService())->SettingList();

    }

    /**
     * @Notes:  汇总查询
     * @Author: 1900
     * @Date: 2022/8/15 16:17
     * @Interface Summary
     * @return \Illuminate\Http\JsonResponse
     */
    public function Summary(Request $request){
        $params = $request->all();
//        var_dump($params);die;
        Validator::check($params, [
            'media_id' => 'required|integer',
        ], [
            'media_id.required' => '数据来源不能为空',
            'media_id.integer' => '来源id必须为int类型',
        ]);
        return $this->apiResponse(function () use ($request) {
            return (new ReportService())->Summary($request->all());
        });
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function dropDown(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'media_id' => 'required|integer',
        ], [
            'media_id.required' => '数据来源不能为空',
            'media_id.integer' => '来源id必须为int类型',
        ]);
        return (new ReportService())->dropDown($request->get('media_id'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('bigdata::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('bigdata::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('bigdata::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
