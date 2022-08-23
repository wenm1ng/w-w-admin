<?php


namespace Modules\Admin\Services;


use Modules\Admin\Models\Category;

class CateService extends BaseApiService
{

    public function index(array $data)
    {
        $model = Category::query();
        $model = $this->queryCondition($model, $data, 'name');
        if (isset($data['is_hot']) && $data['is_hot'] !== null) {
            $model->where('is_hot', $data['is_hot']);
        }
        if (isset($data['is_show']) && $data['is_show'] !== null) {
            $model->where('is_show', $data['is_show']);
        }

        if (!empty($data['created_at'])) {
            !empty($data['created_at'][0]) && $model->where('created_at', ">", $data['created_at'][0]);
            !empty($data['created_at'][1]) && $model->where('created_at', "<", $data['created_at'][1]);
        }


        $list = $model
            ->orderBy('id', 'desc')
            ->get()->keyBy('id')->toArray();

        $items = $list;
        $tree = [];
        foreach ($items as $key => $value) {
            if (isset($items[$value['parant_id']])) {
                $items[$value['parant_id']]['children'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }


        return $this->apiSuccess('', array_values($tree));

    }


    /**
     * 获取所有分类 , 无分页, 吴嵌套
     */
    public function list($data)
    {

        $model = Category::query();
        $model = $this->queryCondition($model, $data, 'name');
        $list = $model->selectRaw('id,name')
            ->orderBy('id', 'desc')
            ->get()->toArray();

        return $this->apiSuccess('', $list);
    }


    /**
     *添加
     **/
    public function store(array $data)
    {
        return $this->commonCreate(Category::query(), $data);
    }

    /**
     *修改页面
     **/
    public
    function edit(int $id)
    {
        return $this->apiSuccess('', Category::find($id)->toArray());
    }

    /**
     *修改提交
     **/
    public
    function update(int $id, array $data)
    {
        return $this->commonUpdate(Category::query(), $id, $data);
    }

    /**
     * 调整状态
     **/
    public
    function status(int $id, array $data)
    {
        return $this->commonStatusUpdate(Category::query(), $id, $data);
    }

    /**
     *软删除
     **/
    public
    function cDestroy(int $id)
    {
        return $this->commonIsDelete(Category::query(), [$id]);
    }


}
