<?php

/**
 *品牌
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\BrandService;
use Modules\Admin\Services\SecUserService;
use Modules\Blog\Http\Requests\CommonIdRequest;

class SecuserController extends BaseApiController
{
    public function index(CommonPageRequest $request, SecUserService $service)
    {
        return $service->index($request->only([
            'page',
            'limit',
            'id',
            'name',
            'created_at',
            'updated_at'
        ]));
    }

    public function store(Request $request, SecUserService $service)
    {
        $rules = [
            'real_name' => 'required',
            "platform_id" => 'required',
            "username" => 'required',
            "phone" => 'required',
        ];
        $messages = [
            'real_name.required' => '名字必传',
        ];

        $request->validate($rules, $messages);

        return $service->store($request->all());
    }

    public function edit(Request $request, SecUserService $service)
    {
        return $service->edit($request->get('id'));
    }

    public function update(Request $request, SecUserService $service)
    {

        return $service->update($request->input('id'), $request->all());
    }

    public function status(Request $request, SecUserService $service)
    {
        return $service->status($request->get('id'), $request->only(['is_alert','status']));
    }


    public function cDestroy(CommonIdRequest $request, SecUserService $service)
    {

        return $service->cDestroy($request->get('id'));

    }


}
