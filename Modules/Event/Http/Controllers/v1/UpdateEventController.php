<?php

namespace Modules\Event\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Http\Requests\CommonIdRequest;
use Modules\Event\Http\Requests\CommonEventRequest;
use Modules\Event\Services\BaseApiService;
use Modules\Event\Services\updateevent\UpdateEventService;
use Modules\Event\Http\Requests\CommonAlertSetting;
use Modules\Event\Http\Requests\CommonPageRequest;
class UpdateEventController extends Controller
{

    /**
     * 详情页
     * Display a listing of the resource.
     * @return Renderable
     */
    public function show(CommonIdRequest $request)
    {
        return (new UpdateEventService())->show($request->get('id'));
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function edit(CommonIdRequest $request)
    {
        return (new UpdateEventService())->edit($request->get('id'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function Bwd_Event_Sh(CommonIdRequest $request)
    {
        return (new UpdateEventService())->Bwd_Event_Sh($request->only([
            'bwd_flag',
            'id',
            'remark',
            'task',
            'type',
            'sbwdid',
            'bwd_designerid',
            'eapectdate'
        ]));
    }


    /*
     * 任务审核
     */
    public function taskAll(Request $request)
    {
//        var_dump($request->all());die;
        return (new UpdateEventService())->taskAll($request->only([
            'bwd_flag',
            'id',
            'remark',
            'task',
            'type',
            'sbwdid',
            'bwd_designerid',
            'eapectdate'
        ]));
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('event::create');
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
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
//    public function edit($id)
//    {
//        return view('event::edit');
//    }

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
