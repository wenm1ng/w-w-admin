<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-23 15:37
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\WaImageModel;
use Modules\Admin\Models\Wa\WaContentModel;
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
            'favorites_num' => 'desc',
            'likes_num' => 'desc',
            'read_num' => 'desc',
            'id' => 'desc'
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
        $images = WaImageModel::query()->where('wa_id', $id)->pluck('image_url', 'id')->toArray();
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
        $insertData = $this->filterArr($data, 'title,version,occupation,tips,type,data_from,status,tt_id,description,wa_content');

        try {
            DB::beginTransaction();
            $productId = WaContentModel::query()->insertGetId($insertData);
            $data['image_url'] = preg_replace("/(http|https):\/\/.*?\//", config('admin.http_url'), $data['image_url']);
            $imageData = [[
                'product_id' => $productId,
                'image_url' => $data['image_url']
            ]];
            WaImageModel::query()->insert($imageData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();

    }
}
