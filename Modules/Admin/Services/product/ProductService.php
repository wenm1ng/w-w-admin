<?php
/*
@desc
@author     文明<736038880@qq.com>
@date       2022-07-08 14:28
*/
namespace Modules\Admin\Services\product;


use Modules\Admin\Models\Product;
use Modules\Admin\Models\ProductImage;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseApiService
{
    /**
     * @desc       新增商品
     * @author     文明<736038880@qq.com>
     * @date       2022-07-08 17:54
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function add(array $data){
        $insertData = $this->filterArr($data, 'product_name,brand_id,product_type,product_url,price,remark');

        try{
            DB::beginTransaction();
            $productId = Product::query()->insertGetId($insertData);
            $data['image_url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $data['image_url']);
            $imageData = [[
                'product_id' => $productId,
                'save_place' => 1,
                'type' => 1,
                'image_url' => $data['image_url']
            ]];
            if(!empty($data['barcode_url'])){
                $data['barcode_url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $data['barcode_url']);
                $imageData[] = [
                    'product_id' => $productId,
                    'save_place' => 1,
                    'type' => 2,
                    'image_url' => $data['barcode_url']
                ];
            }
            ProductImage::query()->insert($imageData);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();

    }

    /**
     * @desc       修改商品
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:52
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function update(array $params){
        $updateData = $this->filterArr($params, 'product_name,brand_id,product_type,product_url,price,remark');
        try{
            DB::beginTransaction();
            Product::query()->where('id', $params['id'])->update($updateData);

            ProductImage::query()->where('product_id', $params['id'])->where('type', 1)->delete();
            ProductImage::query()->where('product_id', $params['id'])->where('type', 2)->delete();
            $imageData = [];
            if(!empty($params['image_url'])){
                $params['image_url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $params['image_url']);
                $imageData = [[
                    'product_id' => $params['id'],
                    'save_place' => 1,
                    'type' => 1,
                    'image_url' => $params['image_url']
                ]];

                ProductImage::query()->where('product_id', $params['id'])->where('type', 1)->update(['image_url' => $params['image_url']]);
            }
            if(!empty($params['barcode_url'])){
                $params['barcode_url'] = preg_replace("/(http|https):\/\/.*?\//", '/', $params['barcode_url']);
                $imageData[] = [
                    'product_id' => $params['id'],
                    'save_place' => 1,
                    'type' => 2,
                    'image_url' => $params['barcode_url']
                ];
            }
            if(!empty($imageData)){
                ProductImage::query()->insert($imageData);
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }

        return $this->apiSuccess();
    }

    /**
     * @desc       列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-08 17:55
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function list(array $data)
    {
        $where = [];
        if(!empty($data['product_name'])){
            $where['where'][] = ['product_name', 'like', "%{$data['product_name']}%"];
        }
        if(isset($data['status'])){
            $where['where'][] = ['status', '=', $data['status']];
        }

        if(!empty($data['created_at'])){
            $where['between'][] = ['created_at', [$data['created_at'][0], $data['created_at'][1]]];
        }

        if(!empty($data['brand_id'])){
            $where['where'][] = ['brand_id', '=', $data['brand_id']];
        }

        $list = Product::baseQuery($where)
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();

        $this->mergeImage($list['data']);

        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @desc       合并商品图片
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 10:02
     * @param array $list
     */
    public function mergeImage(array &$list){
        $productIds = array_column($list, 'id');
        if(!empty($productIds)){
            $imageLink = ProductImage::query()->whereIn('product_id', $productIds)->get()->toArray();
            $imageLink = $this->setEnumArr($imageLink, 'product_id', 'type');
        }
        foreach ($list as $key => $val) {
            $list[$key]['image_url'] = '';
            $list[$key]['barcode_url'] = '';
            if(isset($imageLink[$val['id'].'_1'])){
                $list[$key]['image_url'] = $this->getHttp(). $imageLink[$val['id'].'_1']['image_url'];
            }
            if(isset($imageLink[$val['id'].'_2'])){
                $list[$key]['barcode_url'] = $this->getHttp(). $imageLink[$val['id'].'_2']['image_url'];
            }
        }
    }

    /**
     * @desc       获取商品详情
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:20
     * @param int $id
     *
     * @return \Modules\Common\Services\JSON
     */
    public function info(int $id){
        $info = Product::query()->where('id', $id)->first();
        if(empty($info)){
            $this->apiError('找不到数据');
        }
        $list = [$info->toArray()];
        $this->mergeImage($list);
        return $this->apiSuccess('', $list[0]);
    }

    /**
     * @desc       商品删除
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 13:21
     * @param array $id
     */
    public function delete(array $id){
        try{
            DB::beginTransaction();
            Product::query()->whereIn('id', $id)->delete();
            ProductImage::query()->whereIn('product_id', $id)->delete();
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }
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
        Product::query()->where('id', $params['id'])->update(['status' => $params['status']]);

        return $this->apiSuccess();
    }
}
