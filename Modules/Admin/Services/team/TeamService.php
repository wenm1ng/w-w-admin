<?php
/**
 * @Name 团队管理
 * @Description
 * @Auther fengbin
 * @Date 20220709
 */
namespace Modules\Admin\Services\team;

use Modules\Admin\Models\BusinessTeam;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\Exceptions\MessageData;

class TeamService extends BaseApiService
{
    /**
     * @name 团队管理
     * @return JSON
     **/
    public function index(array $data)
    {

        $model = BusinessTeam::query()->where('is_del', 1);

        $model = $this->queryCondition($model,$data,'name');
        $list = $model->select()
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    public function add(array $params)
    {
        return $this->commonCreate(BusinessTeam::query(), $params);
    }

    public function detail(int $id)
    {
        if(empty($id)){
            return $this->apiError(MessageData::MISS_PARAMS_ERROR);
        }
        return $this->apiSuccess('',BusinessTeam::select()->find($id)->toArray());
    }

    public function update(int $id, array $data)
    {
        return $this->commonUpdate(BusinessTeam::query(), $id, $data);
    }

    public function del(int $id)
    {
        if(empty($id)){
            return $this->apiError(MessageData::MISS_PARAMS_ERROR);
        }
        return $this->commonUpdate(BusinessTeam::query(), $id, ['is_del' => 2, 'updated_at' => date('Y-m-d H:i:s')], MessageData::DELETE_API_SUCCESS);
    }

}
