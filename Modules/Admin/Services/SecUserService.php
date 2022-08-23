<?php


namespace Modules\Admin\Services;


use Modules\Admin\Models\SecUser;

class SecUserService extends BaseApiService
{

    public function index(array $data)
    {
        $model = SecUser::query();
        $model = $this->queryCondition($model, $data, 'real_name');

        if (isset($data['status']))
            $model->where('status',$data['status']);
        if (isset($data['is_alert']))
            $model->where('is_alert', $data['is_alert']);


        if (!empty($data['created_at'])) {
            !empty($data['created_at'][0]) && $model->where('created_at', ">", $data['created_at'][0]);
            !empty($data['created_at'][1]) && $model->where('created_at', "<", $data['created_at'][1]);
        }


        $list = $model
            ->with([
                'getPlatform' => function ($query) {
                    $query->select('id', 'platform_name');
                },
                'getAdminUser' => function ($query) {
                    $query->select('id','name');
                },
            ])
            ->orderBy('id', 'desc')
            ->paginate($data['limit'])
            ->toArray();

        return $this->apiSuccess('', [
            'list' => $list['data'],
            'total' => $list['total'],
        ]);
    }

    /**
     *添加
     **/
    public function store(array $data)
    {

        $phone=explode(',',$data['phone']);
        $username=explode(',',$data['username']);
        if (count($phone)!=count($username) || $phone===0){
            return $this->apiError("第三方账号和通知手机号需要一一对应");
        }
        $temp_data = [];
        foreach ($phone as $key=>$value){
            $temp_data[$key] = $data;
            $temp_data[$key]['phone'] = $value;
            $temp_data[$key]['username'] = $username[$key];
        }

        return $this->commonAddAll(SecUser::query(), $temp_data);
    }

    /**
     *修改页面
     **/
    public function edit(int $id)
    {

        $data = SecUser::query()->find($id)->toArray();

        return $this->apiSuccess('', $data);
    }

    /**
     *修改提交
     **/
    public function update(int $id, array $data)
    {



        $phone=explode(',',$data['phone']);
        $username=explode(',',$data['username']);
        if (count($phone) >1 || count($username) >1 ){
            return $this->apiError("第三方账号和通知手机号 只能填一个");
        }

        return $this->commonUpdate(SecUser::query(), $id, $data);
    }

    /**
     * 调整状态
     **/
    public function status(int $id, array $data)
    {

        return $this->commonUpdate(SecUser::query(), $id, $data);
    }


    /**
     *软删除
     **/
    public function cDestroy(int $id)
    {
        return $this->commonIsDelete(SecUser::query(), [$id]);
    }


}
