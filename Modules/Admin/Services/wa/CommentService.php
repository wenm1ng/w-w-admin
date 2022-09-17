<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 15:40
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\WaCommentModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class CommentService extends BaseApiService
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

        $list = WaCommentModel::baseQuery($where)
            ->with([
                'user_info'=>function($query){
                    $query->select('user_id','openId','nickName','avatarUrl');
                }
            ])
            ->whereHas('user_info',function($query)use ($params){
                if(!empty($params['nickName'])){
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

    /**
     * @desc       商品删除
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 13:21
     * @param array $id
     */
    public function delete(array $id){
        WaCommentModel::query()->whereIn('id', $id)->delete();

        return $this->apiSuccess();
    }

    /**
     * @desc       修改状态
     * @author     文明<736038880@qq.com>
     * @date       2022-07-12 15:18
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function status(array $params){
        WaCommentModel::query()->where('id', $params['id'])->update(['status' => $params['status']]);

        return $this->apiSuccess();
    }
}
