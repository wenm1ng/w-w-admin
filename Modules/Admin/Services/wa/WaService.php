<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-23 15:37
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\WaImageModel;
use Modules\Admin\Models\Wa\WaContentModel;
use Modules\Admin\Models\Wa\WaTabTitleModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class WaService extends BaseApiService
{
    public function list(array $params){
        $where = [];
        if(!empty($params['version'])){
            $where['where'][] = ['version', '=', $params['version']];
        }
        if(!empty($params['occupation'])){
            $where['where'][] = ['occupation', '=', $params['occupation']];
        }
        if(!empty($params['type'])){
            $where['where'][] = ['type', '=', $params['type']];
        }

        if(!empty($params['create_at'])){
            $where['between'][] = ['create_at', [$params['create_at'][0], $params['create_at'][1]]];
        }

        $where['order'] = [
            'update_at' => 'desc',
            'favorites_num' => 'desc',
            'likes_num' => 'desc',
            'read_num' => 'desc',
        ];

        $list = WaContentModel::baseQuery($where)
            ->paginate($params['limit'])
            ->toArray();

        $this->mergeImageList($list['data']);
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @desc       wa详情
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 17:27
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(int $id){
        $info = WaContentModel::query()->where('id', $id)->first()->toArray();
        $this->mergeImage($id, $info);
        return $this->apiSuccess('', $info);
    }

    /**
     * @desc       合并图片
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 17:29
     * @param int   $id
     * @param array $info
     */
    public function mergeImage(int $id, array &$info){
        $images = WaImageModel::query()->where('wa_id', $id)->select(DB::raw('image_url as url,id'))->get()->toArray();
        $info['images'] = $images;
    }

    /**
     * @desc       合并列表图片
     * @author     文明<736038880@qq.com>
     * @date       2022-08-23 17:47
     * @param array $list
     */
    public function mergeImageList(array &$list){
        $ids = array_column($list, 'id');
        $images = WaImageModel::query()->whereIn('wa_id', $ids)->pluck('image_url', 'wa_id')->toArray();
        foreach ($list as &$val) {
            $val['image_url'] = !empty($images[$val['id']]) ? $images[$val['id']] : '';
        }
    }

    /**
     * @desc       新增wa
     * @author     文明<736038880@qq.com>
     * @date       2022-07-08 17:54
     *
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function add(array $data)
    {
        if(empty($data['images'])){
            $this->apiError('图片不能为空');
        }
        $insertData = $this->filterArr($data, 'title,version,occupation,tips,type,data_from,status,tt_id,description,wa_content,origin_url');
        $this->getTypeByTtId($data['tt_id'], $insertData);

        try {
            DB::beginTransaction();
            $waId = WaContentModel::query()->insertGetId($insertData);
            foreach ($data['images'] as $image) {
                $imageUrl = preg_replace("/(http|https):\/\/.*?\//", config('admin.http_url').'/', $image['url']);
                $imageData[] = [
                    'wa_id' => $waId,
                    'image_url' => $imageUrl
                ];
            }
            if(!empty($imageData)){
                WaImageModel::query()->insert($imageData);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();

    }

    /**
     * @desc       修改wa
     * @author     文明<736038880@qq.com>
     * @date       2022-08-25 14:51
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data){

        if(empty($data['images'])){
            $this->apiError('图片不能为空');
        }

        $updateData = $this->filterArr($data, 'title,version,occupation,tips,tt_id,description,wa_content,origin_url');
        $this->getTypeByTtId($data['tt_id'], $updateData);
        try {
            DB::beginTransaction();
            WaContentModel::query()->where('id', $data['id'])->update($updateData);

            WaImageModel::query()->where('wa_id', $data['id'])->delete();
            foreach ($data['images'] as $image) {
                $imageUrl = preg_replace("/(http|https):\/\/.*?\//", config('admin.http_url').'/', $image['url']);
                $imageData[] = [
                    'wa_id' => $data['id'],
                    'image_url' => $imageUrl
                ];
            }
            if(!empty($imageData)){
                WaImageModel::query()->insert($imageData);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();
    }

    /**
     * @desc       获取tab类型
     * @author     文明<736038880@qq.com>
     * @date       2022-08-25 14:56
     * @param $ttId
     * @param $data
     */
    private function getTypeByTtId($ttId, &$data){
        if(empty($ttId)){
            return;
        }
        $data['type'] = WaTabTitleModel::query()->where('id', $ttId)->value('type');
    }

    /**
     * @desc       修改wa状态
     * @author     文明<736038880@qq.com>
     * @date       2022-08-25 15:05
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(array $params){
        WaContentModel::query()->where('id', $params['id'])->update(['status' => $params['status']]);

        return $this->apiSuccess();
    }
}
