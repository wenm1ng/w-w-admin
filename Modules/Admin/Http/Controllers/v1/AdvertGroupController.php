<?php

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Admin\Services\advert\AdvertGroupService;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\Services\BaseService;

class AdvertGroupController extends BaseApiController
{
    protected BaseApiService $service;

    public function __construct()
    {
        $this->service = new AdvertGroupService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        return $this->service->index($request->only([
            'id', 'admin_id', 'platform', 'platform_id', 'name', 'data', 'status', 'page', 'limit'
        ]), function (Builder $query) use ($request) {
            $query = $this->service->queryCondition($query, $request->all(), 'name');
            !is_null($request->get('platform')) && $query->where('platform', $request->get('platform'));
            !is_null($request->get('platform_id')) && $query->where('platform_id', 'like', $request->get('platform_id') . '%');
            return $query;
        });
    }

    public function show(Request $request)
    {
        return $this->service->show($request->route('id'));
    }

    public function store(Request $request)
    {
        return $this->service->store($request->only([
            'admin_id', 'platform', 'platform_id', 'name', 'data', 'status'
        ]));
    }

    public function update(Request $request)
    {
        return $this->service->update($request->route('id'), $request->only([
            'admin_id', 'platform', 'platform_id', 'name', 'data', 'status'
        ]));
    }

    public function destroy(Request $request)
    {
        return $this->service->destroy($request->route('id'));
    }

}
