<?php

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Admin\Services\advert\AdvertService;
use Modules\Admin\Services\BaseApiService;

class AdvertController extends BaseApiController
{
    protected BaseApiService $service;

    public function __construct()
    {
        $this->service = new AdvertService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        return $this->service->index($request->only([
            'id', 'admin_id', 'group_id', 'campaign_id', 'creative_id',
            'platform', 'advertiser_id', 'name', 'status', 'data', 'page', 'limit'
        ]), function (Builder $query) use ($request) {
            $query = $this->service->queryCondition($query, $request->all(), 'name');
            !is_null($request->get('platform')) && $query->where('platform', $request->get('platform'));
            !is_null($request->get('group_id')) && $query->where('group_id', $request->get('group_id'));
            !is_null($request->get('campaign_id')) && $query->where('campaign_id', $request->get('campaign_id'));
            !is_null($request->get('creative_id')) && $query->where('creative_id', $request->get('creative_id'));
            !is_null($request->get('advertiser_id')) && $query->where('advertiser_id', $request->get('advertiser_id'));
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
            'admin_id', 'group_id', 'campaign_id', 'creative_id',
            'platform', 'advertiser_id', 'name', 'status', 'platform', 'data'
        ]));
    }

    public function update(Request $request)
    {
        return $this->service->update($request->route('id'), $request->only([
            'admin_id', 'group_id', 'campaign_id', 'creative_id',
            'platform', 'advertiser_id', 'name', 'status', 'platform', 'data'
        ]));
    }

    public function destroy(Request $request)
    {
        return $this->service->destroy($request->route('id'));
    }

}
