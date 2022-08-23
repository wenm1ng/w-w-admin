<?php
/**
 * @Name 团队管理
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\team\TeamService;
use Illuminate\Support\Facades\Validator;
class TeamController extends BaseApiController
{
    /**
     * @name 团队列表
     * fb  20220709
     **/
    public function index(CommonPageRequest $request)
    {
        return (new TeamService())->index($request->only([
            'page',
            'limit',
            'name'
        ]));
    }

    public function addTeams(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'remark' => 'required|max:255|min:1'
        ]);var_dump($validator->getMessageBag());exit;
        if ($validator->fails()) {
            return redirect('')
                ->withErrors($validator->errors())
                ->withInput();

            var_dump($validator->getMessageBag());var_dump($request->all());exit;
            return (new TeamService())->add($request->only([
                'name',
                'team_number',
                'platform_id',
                'remark'
            ]));
        }
        $validated = $request->validated();var_dump($validated);exit;*/
        return (new TeamService())->add($request->only([
            'name',
            'team_number',
            'platform_id',
            'remark'
        ]));
    }

    public function detail(Request $request)
    {
        return (new TeamService())->detail($request->get('id'));
    }

    public function updateTeam(Request $request)
    {
        return (new TeamService())->update($request->get('id'), $request->only(['name','team_number', 'platform_id', 'remark']));
    }

    public function delTeam(Request $request)
    {
        return (new TeamService())->del($request->get('id'));
    }

}
