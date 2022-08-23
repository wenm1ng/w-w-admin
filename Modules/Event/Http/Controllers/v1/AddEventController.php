<?php

namespace Modules\Event\Http\Controllers\v1;

//use Illuminate\Contracts\Support\Renderable;
//use Illuminate\Http\Request;
use Modules\Event\Http\Requests\CommonPageRequest;
use Modules\Event\Http\Requests\CommonAlertSetting;
use Modules\Event\Services\BaseApiService;
//use Modules\Event\Services\report\ReportService;
class AddEventController extends BaseApiController
{
    /**
     * 列表查询
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CommonPageRequest $request)
    {
//        echo 3443;die;

    }

    /*
    *
    *報警設置
    */

    public function AlertSetting(CommonAlertSetting $request){

    }

    public function SettingList()
    {



    }

    /*
     *
     *发送钉钉提醒
     */
    public function Summary(){

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function dropDown()
    {

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

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


}
