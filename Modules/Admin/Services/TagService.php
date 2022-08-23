<?php


namespace Modules\Admin\Services;


use Modules\Admin\Models\Tag;
use Illuminate\Support\Facades\DB;
class TagService extends BaseApiService
{

    public function index(array $data)
    {
        $model = Tag::query();
        $model = $this->queryCondition($model, $data, 'name');


        if (!empty($data['created_at'])) {
            !empty($data['created_at'][0]) && $model->where('created_at', ">", $data['created_at'][0]);
            !empty($data['created_at'][1]) && $model->where('created_at', "<", $data['created_at'][1]);
        }
        if(!empty($data['search_value'])){
            $model->where('name', "like", "%{$data['search_value']}%");
        }

        if(isset($data['tag_ids']) && is_array($data['tag_ids'])){
            $model->whereIn('id',$data['tag_ids']);
            $model->orWhere('is_delete','0');
            $model->orderByRaw("FIELD(id, " . implode(", ", $data['tag_ids']) . ") desc");
        }
        $list = $model
            ->orderBy('created_at', 'desc')
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

        return $this->commonCreate(Tag::query(), $data);
    }

    /**
     *修改页面
     **/
    public function edit(int $id)
    {

        $data = Tag::query()->find($id)->toArray();

        return $this->apiSuccess('',$data);
    }

    /**
     *修改提交
     **/
    public function update(int $id, array $data)
    {
        return $this->commonUpdate(Tag::query(), $id, $data);
    }

    /**
     * 调整状态
     **/
    public function status(int $id, array $data)
    {
        return $this->commonStatusUpdate(Tag::query(), $id, $data);
    }


    /**
     *软删除
     **/
    public function cDestroy(int $id)
    {
        return $this->commonIsDelete(Tag::query(), [$id]);
    }


}
