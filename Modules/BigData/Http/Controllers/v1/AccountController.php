<?php

namespace Modules\BigData\Http\Controllers\v1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BigData\Http\Requests\CommonPageRequest;
use Modules\BigData\Services\account\AccountService;
use Modules\BigData\Services\sendmessage\SendMessageService;
class AccountController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(CommonPageRequest $request)
    {
        return (new AccountService())->index($request->only([
            'page',
            'limit',
            'name',
            'created_at',
            'updated_at'
        ]));
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
