<?php

/**
 *品牌
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\TagService;
use Modules\Blog\Http\Requests\CommonIdRequest;

class TagController extends BaseApiController
{
    public function index(CommonPageRequest $request, TagService $service)
    {
        return $service->index($request->only([
            'page',
            'limit',
            'id',
            'name',
            'tag_ids',
            'created_at',
            'updated_at',
            'search_value'
        ]));
    }

    public function store(Request $request, TagService $service)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'title.required' => '名字必传',
        ];

       $request->validate($rules, $messages);

        return $service->store($request->only([
            "name",
        ]));
    }

    public function edit(Request $request, TagService $service)
    {
        return $service->edit($request->get('id'));
    }

    public function update(Request $request, TagService $service)
    {

        return $service->update($request->input('id'), $request->only([
            'name',
        ]));
    }


    public function cDestroy(CommonIdRequest $request, TagService $service)
    {

        return $service->cDestroy($request->get('id'));

    }




}
