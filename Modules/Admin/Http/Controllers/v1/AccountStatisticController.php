<?php

namespace Modules\Admin\Http\Controllers\v1;

//use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\BaseService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Services\accountstatistic\AccountStatisticService;
class AccountStatisticController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function zts_statistics(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'startDate' => 'required',
            'endDate' => 'required',
            'pageSize' => 'required|integer',
        ], [
            'startDate.required' => '页数不能为空',
            'endDate.required' => '页数不能为空',
            'pageSize.required' => '每页数量不能为空',
            'pageSize.integer' => '每页数量必须为int类型',
        ]);
        return (new AccountStatisticService())->zts_statistics($request->all());
    }

    public function zts_redis()
    {
        return (new AccountStatisticService())->RedisList();

    }

    public function test()
    {
        return (new AccountStatisticService())->test();

    }

}
