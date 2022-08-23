<?php

namespace Modules\Admin\Http\Controllers\v1;



use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Http\Requests\CommonStatusRequest;
use Modules\Admin\Http\Requests\StoreCreateRequest;
use Modules\Admin\Http\Requests\StoreUpdateRequest;
use Modules\Admin\Services\store\StoreService;
use Illuminate\Http\Request;
class StoreController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CommonPageRequest $request)
    {
        //echo 343;die;
        return (new StoreService())->index($request->only([
            'page',
            'limit',
            'name',
            'status',
            'created_at',
            'updated_at',
            'number',
            'url',
            'plat_type'
        ]));
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
    public function store(StoreCreateRequest $request)
    {
        return (new StoreService())->store($request->only([
            'page',
            'limit',
            'name',
            'status',
            'number',
            'url',
            'plat_type'
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
    public function edit(CommonIdRequest $request)
    {
        return (new StoreService())->edit($request->get('id'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreUpdateRequest $request)
    {
        return (new StoreService())->update($request->get('id'),$request->only([
            'name',
            'status',
            'number',
            'url',
            'plat_type'
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 会员id
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new StoreService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        return (new StoreService())->delete($request->get('id'));
    }
    public  function  delete(Request $request){


    }
}
