<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 16:45
 */
namespace Modules\Admin\Services\material;


use Modules\Admin\Models\MaterialTagLink;
use Modules\Admin\Models\Material;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Tag;
use Modules\Admin\Models\Department;
use Modules\Admin\Models\AuthGroup;
use Modules\Admin\Services\admin\AdminService;

class MaterialService extends BaseApiService
{

    /**
     * @desc       列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:23
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function list(array $data)
    {
        $where = [
            'where' => [
                ['is_deleted', '=', 0]
            ]
        ];
        if(!empty($data['search_column'])){
            $where['where'][] = ['search_column', 'like', "%{$data['search_column']}%"];
        }

        if(isset($data['type'])){
            $where['where'][] = ['type', '=', $data['type']];
        }

        if(isset($data['category'])){
            $where['where'][] = ['category', '=', $data['category']];
        }

        if(isset($data['level'])){
            $where['where'][] = ['level', '=', $data['level']];
        }

        if(isset($data['from_type'])){
            $where['where'][] = ['from_type', '=', $data['from_type']];
        }

        if(!empty($data['origin_url'])){
            $where['where'][] = ['origin_url', 'like', "%{$data['origin_url']}%"];
        }

        if(!empty($data['created_at'])){
            $where['between'][] = ['created_at', [$data['created_at'][0].' 00:00:00', $data['created_at'][1]. ' 23:59:59']];
        }

        if(!empty($data['tag_ids'])){
            $materialIds = MaterialTagLink::query()->whereIn('tag_id', $data['tag_ids'])->pluck('material_id')->toArray();
            if(!empty($materialIds)){
                $where['whereIn'][] = ['id', array_unique($materialIds)];
            }else{
                $where['where'][] = ['id', '=', 0];
            }
        }

        $userInfo = \Auth::user();
        $level = 1;
        if(!empty($userInfo->group_id)){
            $level = AuthGroup::query()->where('id', $userInfo->group_id)->value('level');
        }
        $where['where'][] = ['level', '<=', $level];

        $where['order'] = [
            'download_count' => 'desc',
            'updated_at' => 'desc',
            'id' => 'desc'
        ];
        $list = Material::baseQuery($where)
            ->paginate($data['limit'])
            ->toArray();

        $this->mergeImage($list['data']);
        $this->mergeTag($list['data']);
        (new AdminService())->mergeAdminName($list['data']);
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }


    /**
     * @desc       处理图片数据
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:23
     * @param array $list
     */
    public function mergeImage(array &$list){
        foreach ($list as &$val) {
            $val['url'] = $this->getHttp(). $val['url'];
            $val['file_name'] = trim(strrchr($val['url'], '/'),'/');
        }
    }

    public function mergeTag(array &$list){
        //获取标签名
        $ids = array_column($list, 'id');
        if(empty($ids)){
            return;
        }
        $tagList = MaterialTagLink::query()->whereIn('material_id', $ids)->get()->toArray();
        $tagIds = array_unique(array_column($tagList, 'tag_id'));
        $tagList = $this->arrayGroup($tagList, 'material_id', 'tag_id');
        if(empty($tagIds)){
            return;
        }
        $tagLink = Tag::query()->whereIn('id', $tagIds)->where('is_delete', 0)->pluck('name', 'id')->toArray();
        $newLink = [];
        foreach ($tagList as $materialId => $tagIds) {
            foreach ($tagIds as $tagId) {
                if(empty($tagLink[$tagId])){
                    continue;
                }
                $newLink[$materialId][] = $tagLink[$tagId];
            }
        }
        foreach ($list as &$val) {
            $val['tags'] = $newLink[$val['id']] ?? [];
        }
    }

    /**
     * @desc       详情
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:24
     * @param int $id
     *
     * @return \Modules\Common\Services\JSON
     */
    public function info(int $id){
        $info = Material::query()->where('id', $id)->where('is_deleted', 0)->first();
        if(empty($info)){
            $this->apiError('找不到数据');
        }
        $list = [$info->toArray()];
        $this->mergeImage($list);
        $list[0]['tag_ids'] = $this->getTag($id);
        return $this->apiSuccess('', $list[0]);
    }

    /**
     * @desc       删除素材
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:32
     * @param int $id
     *
     * @return \Modules\Common\Services\JSON
     */
    public function delete(array $id){
        $info = Material::query()->whereIn('id', $id)->first();
        if(empty($info)){
            $this->apiError('找不到数据');
        }

        try{
            DB::beginTransaction();
            Material::query()->whereIn('id', $id)->update(['is_deleted' => 1]);
//            MaterialTagLink::query()->where('material_id', $id)->delete();
//            $this->deleteUrlFile($info['url']);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();
    }

    /**
     * @desc       新增素材
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 9:53
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function add(array $params){
        $insertData = $this->filterArr($params, 'type,url,name,remark,from_type,category,product_name,level');
        $insertData['status'] = 1;
        $userInfo = \Auth::user();
        $insertData['user_id'] = !empty($userInfo->id) ? $userInfo->id : 0;
        //获取顶级部门id
        if(!empty($userInfo->department_id)){
            $departmentId = Department::query()->where('id', $userInfo->department_id)->select(DB::raw("SUBSTRING_INDEX(path, ',', 1) as parent_id"))->value('parent_id');
        }
        $insertData['department_id'] = !empty($departmentId) ? $departmentId : 0;
        $mainIds = [];

        try{
            DB::beginTransaction();
            $count = count($params['urls']);
            foreach ($params['urls'] as $val) {
                if($count > 1){
                    $insertData['name'] = str_replace(strrchr($val['name'], '.'), '', $val['name']);
                }
                $insertData['url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $val['url']);
                $insertData['file_md5'] = !empty($val['file_md5']) ? $val['file_md5'] : '';
                $insertData['resolution'] = !empty($val['resolution']) ? $val['resolution'] : '';
                $insertData['duration'] = !empty($val['duration']) ? $val['duration'] : 0;
                $insertData['search_column'] = ($insertData['name'] ?? '').'/'.($insertData['remark'] ?? '').'/'.($insertData['product_name'] ?? '').'/'.($userInfo->name ?? '');
                $mainIds[] = Material::query()->insertGetId($insertData);
            }

            $linkData = [];
            foreach ($params['tag_ids'] as $id) {
                foreach ($mainIds as $materialId) {
                    $linkData[] = [
                        'material_id' => $materialId,
                        'tag_id' => $id
                    ];
                }
            }
            if(!empty($linkData)){
                MaterialTagLink::query()->insert($linkData);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess('', ['id' => $mainIds]);
    }

    /**
     * @desc       修改素材
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:03
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function update(array $params){
        $updateData = $this->filterArr($params, 'name,type,remark,file_md5,category,product_name,from_type,level');
//        $updateData['url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $params['url']);
        $userInfo = \Auth::user();

        try{
            DB::beginTransaction();
            $updateData['search_column'] = ($updateData['name'] ?? '').'/'.($updateData['remark'] ?? '').'/'.($updateData['product_name'] ?? '').'/'.($userInfo->name ?? '');
            $updateData['update_user_id'] = $userInfo->id;
            Material::query()->where('id', $params['id'])->update($updateData);

            $linkData = [];
            MaterialTagLink::query()->where('material_id', $params['id'])->delete();

            foreach ($params['tag_ids'] as $id) {
                $linkData[] = [
                    'material_id' => $params['id'],
                    'tag_id' => $id
                ];
            }
            if(!empty($linkData)){
                MaterialTagLink::query()->insert($linkData);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }
        return $this->apiSuccess();
    }

    /**
     * @desc       删除文件
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:12
     * @param string $url
     *
     * @return \Modules\Common\Services\JSON
     */
    public function deleteUrlFile(string $url){
        $url = preg_replace("/(http|https):\/\/.*?\//", '/', $url);
        $url = base_path('public').$url;
        if(file_exists($url)){
            unlink($url);
        }
        return $this->apiSuccess();
    }

    /**
     * @desc       修改状态
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 10:33
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function status(array $params){
        Material::query()->where('id', $params['id'])->update(['status' => $params['status']]);

        return $this->apiSuccess();
    }

    protected function getTag(int $id){
        $model = new MaterialTagLink();
        $tagIds = $model->from('material_tag_link as a')
            ->where('a.material_id', $id)
            ->where('tags.is_delete', '0')
            ->leftJoin('tags as tags', 'tags.id', '=', 'a.tag_id')
            ->pluck('tags.id')->toArray();
//        $tagIds = MaterialTagLink::query()->where('material_id', $id)->pluck('tag_id')->toArray();
        return $tagIds;
    }

    /**
     * @desc       批量修改素材标签
     * @author     文明<736038880@qq.com>
     * @date       2022-08-18 18:38
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchAddTags(array $params){
        $tagIdData = [];
        try{
            DB::beginTransaction();
            $model = MaterialTagLink::query();
            if($params['type'] == 1){
                //新增
                foreach ($params['tag_ids'] as $tagId) {
                    foreach ($params['ids'] as $id) {
                        $tagIdData[] = [
                            'material_id' => $id,
                            'tag_id' => $tagId
                        ];
                    }
                }
                $model->insert($tagIdData);
            }else{
                //删除
                $model->whereIn('material_id', $params['ids'])->whereIn('tag_id', $params['tag_ids'])->delete();
            }

            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();
    }

    /**
     * @desc       批量累加素材下载量
     * @author     文明<736038880@qq.com>
     * @date       2022-08-22 13:22
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchAddDownloadNum(array $params){
        Material::query()->whereIn('id', $params['ids'])->update(['download_num' => DB::raw('download_num + 1')]);
        return $this->apiSuccess();
    }
}
