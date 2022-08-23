<?php

/**
 *品牌
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\BrandService;
use Modules\Admin\Services\CateService;
use Modules\Blog\Http\Requests\CommonIdRequest;

class BrandController extends BaseApiController
{
    public function index(CommonPageRequest $request, BrandService $service)
    {
        return $service->index($request->only([
            'page',
            'limit',
            'id',
            'name',
            'parant_id',
            'level',
            'sort',
            'is_hot',
            'created_at'
        ]));
    }

    public function store(Request $request, BrandService $service)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'title.required' => '需要分类名字',
        ];

       $request->validate($rules, $messages);

        return $service->store($request->only([
            "name",
            "desc",
            "is_hot",
            "cat_id",
            "logo_id",
        ]));
    }

    public function edit(Request $request, BrandService $service)
    {
        return (new BrandService())->edit($request->get('id'));
    }

    public function update(Request $request, BrandService $service)
    {
        return (new BrandService())->update($request->get('id'), $request->only([
            'name',
            'parant_id',
            'parent_id_path',
            'level',
            'sort',
            'is_show',
            'is_hot',
        ]));
    }

    public function status(Request $request, BrandService $service)
    {
        return $service->status($request->get('id'), $request->only(['is_hot']));
    }

    public function cDestroy(CommonIdRequest $request, BrandService $service)
    {

        return $service->cDestroy($request->get('id'));

    }

    public function getCate(Request $request, CateService $service)
    {
//        dd($request->all(),$request->only(['name']));


        return $service->list($request->only(['name']));

    }


}
