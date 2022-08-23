<?php

/**
 *分类管理控制器
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Models\Category;
use Modules\Admin\Services\CateService;
use Modules\Blog\Http\Requests\CommonIdRequest;

class CategoryController extends BaseApiController
{
    public function index(CommonPageRequest $request, CateService $service)
    {
        return $service->index($request->only([
            'page',
            'limit',
            'id',
            'name',
            'parant_id',
            'parent_id_path',
            'level',
            'sort',
            'is_show',
            'is_hot',
            'created_at'
        ]));
    }

    public function store(Request $request, CateService $service)
    {
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'title.required' => '需要分类名字',
        ];

        $post = $request->validate($rules, $messages);

        return $service->store($request->only([
            'name',
            'parant_id',
            'parent_id_path',
            'level',
            'sort',
            'is_show',
            'is_hot',
        ]));
    }

    public function edit(Request $request, CateService $service)
    {
        return (new CateService())->edit($request->get('id'));
    }

    public function update(Request $request, CateService $service)
    {
        return (new CateService())->update($request->get('id'), $request->only([
            'name',
            'parant_id',
            'parent_id_path',
            'level',
            'sort',
            'is_show',
            'is_hot',
        ]));
    }

    public function status(Request $request, CateService $service)
    {
        return $service->status($request->get('id'), $request->only(['is_show']));
    }

    public function cDestroy(CommonIdRequest $request, CateService $service)
    {

        return $service->cDestroy( $request->get('id'));

    }

}
