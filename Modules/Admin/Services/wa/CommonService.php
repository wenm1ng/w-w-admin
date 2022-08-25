<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-24 10:01
 */
namespace Modules\Admin\Services\wa;


use Modules\Admin\Models\Wa\VersionModel;
use Modules\Admin\Models\Wa\OccupationModel;
use Modules\Admin\Models\Wa\WaTabModel;
use Modules\Admin\Models\Wa\WaTabTitleModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;

class CommonService extends BaseApiService
{
    /**
     * @desc       获取版本列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-24 10:03
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVersionList(array $params)
    {
        $list = VersionModel::query()->get()->toArray();
        return $this->apiSuccess('', $list);
    }

    /**
     * @desc       职业列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-24 17:07
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOcList(array $params){
//        if(empty($params['version'])){
//            $this->apiError('版本不能为空');
//        }
//        $where = [
//            'where' => [
//                ['version', '=', $params['version']]
//            ]
//        ];
        $list = OccupationModel::query()->get()->toArray();
        $list = arrayGroup($list, 'version');
        return $this->apiSuccess('', $list);
    }

    /**
     * @desc       tab列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-24 17:44
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTabList(array $params){
//        if(empty($params['version'])){
//            $this->apiError('版本不能为空');
//        }
        $link = WaTabModel::query()->where('status', 1)->pluck('type_name', 'type')->toArray();

        $tabList = WaTabTitleModel::query()->where('status', 1)->get()->toArray();

        foreach ($tabList as &$val) {
            $val['type_name'] = !empty($link[$val['type']]) ? $link[$val['type']] : '';
        }

        $tabList = arrayGroup($tabList, 'version');

        return $this->apiSuccess('', $tabList);
    }
}
