<?php

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\adportlist\AdPortListService;
class AdPortListController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return (new AdPortListService())->accountlist($request->get('id'));
    }
    public function cs()
    {
        return (new AdPortListService())->cs();
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getPlanReportList(Request $request)
    {
        return (new AdPortListService())->getPlanReportList($request->get('id'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getUserReportList(Request $request)
    {
        return (new AdPortListService())->getUserReportList($request->get('id'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getUsersMake(Request $request)
    {
        return (new AdPortListService())->getUsersMake($request->get('id'));
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
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
