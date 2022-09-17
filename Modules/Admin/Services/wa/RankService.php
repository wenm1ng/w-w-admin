<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 17:59
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\LeaderBoardCrontabModel;
use Modules\Admin\Models\Wa\LeaderBoardModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class RankService extends BaseApiService
{
    public function list(array $params)
    {
        $where = [];

        if (!empty($params['type'])) {
            $where['where'][] = ['type', '=', $params['type']];
        }

        if (isset($params['status']) && $params['status'] >= 0) {
            $where['where'][] = ['status', '=', $params['status']];
        }

        if (!empty($params['create_at'])) {
            $where['between'][] = ['create_at', [$params['create_at'][0], $params['create_at'][1]]];
        }

        $where['order'] = [
            'create_at' => 'desc',
        ];

        $fields = 'id,status,wa_id,type,user_id,content,create_at';

        $list = WaComment::baseQuery($where)
            ->with([
                'user_info' => function ($query) {
                    $query->select('user_id', 'openId', 'nickName', 'avatarUrl');
                }
            ])
            ->whereHas('user_info', function ($query) use ($params) {
                if (!empty($params['nickName'])) {
                    $query->where('nickName', 'like', '%' . $params['nickName'] . '%');
                }
            })
            ->select(DB::raw($fields))
            ->paginate($params['limit'])
            ->toArray();

//        $this->mergeImageList($list['data']);
        return $this->apiSuccess('', [
            'list' => $list['data'],
            'total' => $list['total']
        ]);
    }
}
