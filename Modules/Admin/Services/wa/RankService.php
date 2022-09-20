<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 17:59
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\LeaderBoardCrontabModel;
use Modules\Admin\Models\Wa\LeaderBoardModel;
use Modules\Admin\Models\Wa\HelpAnswerModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class RankService extends BaseApiService
{
    /**
     * @desc       获取排行榜列表
     * @author     文明<736038880@qq.com>
     * @date       2022-09-19 13:17
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(array $params)
    {
        $where = [
            'between' => [
                ['week', [$params['week'][0], $params['week'][1]]]
            ]
        ];


        $where['order'] = [
            'score' => 'desc',
            'adopt_num' => 'desc',
            'description_num' => 'desc',
        ];

        $date = getWowWeekYear(date('Y-m-d'));
        $nowWeek = $date['week'];

        if($nowWeek > $params['week'][0]){
            //以往数据
            $model = LeaderBoardCrontabModel::baseQuery($where);
        }else{
            //获取当前周的数据
            $model = LeaderBoardModel::baseQuery($where);
        }

        $list = $model
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
            ->paginate($params['limit'])
            ->toArray();

//        $this->mergeImageList($list['data']);
        return $this->apiSuccess('', [
            'list' => $list['data'],
            'total' => $list['total']
        ]);
    }

    /**
     * @desc       获取回答列表
     * @author     文明<736038880@qq.com>
     * @date       2022-09-19 15:20
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnswerList(array $params){
        $where = [
            'where' => [
                ['user_id', '=', $params['user_id']]
            ],
            'between' => [
                ['create_at', [$params['create_at'][0], $params['create_at'][1]]]
            ]
        ];

        $where['order'] = [
            'create_at' => 'desc',
        ];

        $list = HelpAnswerModel::baseQuery($where)
            ->with([
                'user_info' => function ($query) {
                    $query->select('user_id', 'openId', 'nickName', 'avatarUrl');
                }
            ])
            ->whereHas('user_info', function ($query) use ($params) {
                $query->where('user_id', '=', $params['user_id']);
            })
            ->get()
            ->toArray();

        return $this->apiSuccess('', [
            'list' => $list,
        ]);
    }

    /**
     * @desc       删除回答
     * @author     文明<736038880@qq.com>
     * @date       2022-09-20 10:26
     * @param array $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delAnswer(array $id){
        $info = HelpAnswerModel::query()->whereIn('id', $id)->first();
        if(empty($info)){
            $this->apiError('回答不存在');
        }
        HelpAnswerModel::query()->whereIn('id', $id)->delete();
        LeaderBoardModel::incrementScore($info['user_id'], 2, $info['create_at'], -1, $info['description_num']);
        return $this->apiSuccess();
    }

    /**
     * @desc       修改回答状态
     * @author     文明<736038880@qq.com>
     * @date       2022-09-20 11:11
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function answerStatus(array $params){
        $info = HelpAnswerModel::query()->where('id', $params['id'])->first();
        if(empty($info)){
            $this->apiError('回答不存在');
        }
        HelpAnswerModel::query()->where('id', $params['id'])->update(['status' => $params['status']]);
        if($params['status']){
            LeaderBoardModel::incrementScore($info['user_id'], 2, $info['create_at'], 1, $info['description_num']);
        }else{
            LeaderBoardModel::incrementScore($info['user_id'], 2, $info['create_at'], -1, $info['description_num']);
        }
        return $this->apiSuccess();
    }

}
