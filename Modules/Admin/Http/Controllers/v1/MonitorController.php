<?php

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\CommonKeysignRequest;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Http\Requests\CommonStatusRequest;
use Modules\Admin\Http\Requests\MonitorCreateRequest;
use Modules\Admin\Http\Requests\MonitorPullRequest;
use Modules\Admin\Http\Requests\StoreUpdateRequest;
use Modules\Admin\Services\monitor\MonitorService;

class MonitorController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CommonPageRequest $request)
    {
        //var_dump($request->name);die;
        return (new MonitorService())->index($request->only([
            'page',
            'limit',
            'name',
            'plat_name',
            'plat_id',
            'campaignname',
            'plan_name',
            'status',
            'created_at',
            'updated_at'
        ]));
    }

    public function platList(Request $request)
    {
        return (new MonitorService())->platList();

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function dropdown()
    {
        return (new MonitorService())->dropDown();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function dropDownPlanGroup(MonitorPullRequest $request)
    {
        //
        return (new MonitorService())->dropDown_Plan_Group($request->only([
            'name'
        ]));
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function dropDownPlan(MonitorPullRequest $request)
    {
        //
        return (new MonitorService())->dropDown_Plan($request->only([
            'name',
            'campaignname'
        ]));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function rules(CommonPageRequest $request)
    {
        return (new MonitorService())->rules($request->only([
            'page',
            'limit',
            'name',
            'status',
            'group_id',
            'created_at',
            'updated_at'
        ]));
    }


    /**
     * 编辑页面
     * @param int $id
     * @return Renderable
     */
    public function rulesedit(CommonIdRequest $request)
    {
        return (new MonitorService())->rulesedit($request->get('id'));
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function rulesupdate(MonitorCreateRequest $request)
    {
        return (new MonitorService())->rulesupdate($request->get('id'),$request->only([
            'name',
            'balance',
            'budget',
            'collectioncost',
            'group_id',
            'plan',
            'plat_type',
            'key_sign',
            'returnoninvestment',
            'status'
        ]));
    }

    /**
     * 编辑页面
     * @param int $id
     * @return Renderable
     */
    public function rulesstatus(CommonStatusRequest $request)
    {
        return (new MonitorService())->rulesstatus($request->get('id'),$request->only(['status']));
    }

    /**
     * 开关单独修改
     * @param int $id
     * @return Renderable
     */
    public function rulesSwitch(CommonKeysignRequest $request)
    {
        return (new MonitorService())->rulesSwitch($request->get('id'),$request->only(['key_sign']));
    }

    //删除规则
    public function rulesdestroy(CommonIdRequest $request)
    {
        return (new MonitorService())->rulesdelete($request->get('id'));

    }

    //添加规则
    public function rulesadd(MonitorCreateRequest $request)
    {
        //echo 343;die;
        return (new MonitorService())->rulesAdd($request->only([
            'name',
            'balance',
            'budget',
            'collectioncost',
            'group_id',
            'plan',
            'plat_type',
            'key_sign',
            'returnoninvestment',
            'status'
        ]));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
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
