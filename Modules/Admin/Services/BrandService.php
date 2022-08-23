<?php


namespace Modules\Admin\Services;


use Modules\Admin\Models\Brand;

class BrandService extends BaseApiService
{

    public function index(array $data)
    {
        $model = Brand::query();
        $model = $this->queryCondition($model, $data, 'name');
        if (isset($data['is_hot']) && $data['is_hot'] !== null) {
            $model->where('is_hot', $data['is_hot']);
        }

        if (!empty($data['created_at'])) {
            !empty($data['created_at'][0]) && $model->where('created_at', ">", $data['created_at'][0]);
            !empty($data['created_at'][1]) && $model->where('created_at', "<", $data['created_at'][1]);
        }


        $list = $model
            ->with([
                'getCate' => function ($query) {
                    $query->select('id', 'name');
                },
                'getimg' => function ($query) {
                    $query->select('id', 'url');
                },
            ])
            ->orderBy('id', 'desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('', [
            'list' => $list['data'],
            'total' => $list['total'],
            'host' => $this->getHttp()
        ]);
    }

    /**
     *添加
     **/
    public function store(array $data)
    {

        //
        return $this->commonCreate(Brand::query(), $data);
    }

    /**
     *修改页面
     **/
    public function edit(int $id)
    {

        $data = Brand::with([
            'getimg' => function ($query) {
                $query->select('id', 'url');
            },
        ])->find($id)->toArray();

        return $this->apiSuccess('',$data);
    }

    /**
     *修改提交
     **/
    public function update(int $id, array $data)
    {
        return $this->commonUpdate(Brand::query(), $id, $data);
    }

    /**
     * 调整状态
     **/
    public function status(int $id, array $data)
    {
        return $this->commonStatusUpdate(Brand::query(), $id, $data);
    }


    /**
     *软删除
     **/
    public function cDestroy(int $id)
    {
        return $this->commonIsDelete(Brand::query(), [$id]);
    }


}
