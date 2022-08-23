<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-26 14:08
 */
namespace Modules\Admin\Services\platform;


use Modules\Admin\Models\Platform\Platform;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class PlatformService extends BaseApiService
{
    /**
     * @desc       新增平台
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:09
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function add(array $data){
        $insertData = $this->filterArr($data, 'platform_name,platform_type,remark');

        Platform::query()->insertGetId($insertData);

        return $this->apiSuccess();

    }

    /**
     * @desc       平台修改
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:35
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function update(array $params){
        $updateData = $this->filterArr($params, 'platform_name,platform_type,remark');

        Platform::query()->where('id', $params['id'])->update($updateData);

        return $this->apiSuccess();
    }

    /**
     * @desc       列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:36
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function list(array $data)
    {
        $where = ['is_delete' => 0];
        if(!empty($data['platform_name'])){
            $where['where'][] = ['platform_name', 'like', "%{$data['platform_name']}%"];
        }
        if(isset($data['status'])){
            $where['where'][] = ['status', '=', $data['status']];
        }

        if(!empty($data['created_at'])){
            $where['between'][] = ['created_at', [$data['created_at'][0], $data['created_at'][1]]];
        }

        $list = Platform::baseQuery($where)
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();

        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @desc       平台详情
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:37
     * @param int $id
     *
     * @return \Modules\Common\Services\JSON
     */
    public function info(int $id){
        $info = Platform::query()->where('id', $id)->where('is_delete', 0)->first();
        if(empty($info)){
            $this->apiError('找不到数据');
        }
        $info = $info->toArray();
        return $this->apiSuccess('', $info);
    }

    /**
     * @desc       标记删除平台
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:39
     * @param array $id
     *
     * @return \Modules\Common\Services\JSON
     */
    public function delete(array $id){
        Platform::query()->whereIn('id', $id)->update(['is_delete' => 1]);
        return $this->apiSuccess();
    }

    /**
     * @desc       修改状态
     * @author     文明<736038880@qq.com>
     * @date       2022-07-26 14:39
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function status(array $params){
        Platform::query()->where('id', $params['id'])->update(['status' => $params['status']]);

        return $this->apiSuccess();
    }
}
