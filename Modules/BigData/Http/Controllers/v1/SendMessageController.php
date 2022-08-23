<?php

namespace Modules\BigData\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BigData\Http\Requests\CommonPageRequest;
use Modules\BigData\Services\BaseApiService;
use Modules\BigData\Services\sendmessage\SendMessageService;
use Modules\BigData\Services\sendmessage\SendMessagePlanService;
class SendMessageController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('bigdata::index');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function callQuery(Request $request)
    {
        return (new SendMessageService())->callQuery($request->all());
    }


    public function newEarly()
    {
        return (new SendMessageService())->newEarly();

    }

    public function AnewEarly()
    {
        return (new SendMessagePlanService())->AnewEarly();

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
