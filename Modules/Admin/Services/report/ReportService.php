<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-16 17:25
 */
namespace Modules\Admin\Services\report;


use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\AccountPlanReport;
use Modules\Admin\Models\AccountSummaryReport;
use Modules\Admin\Models\BrandAccountReport;
use Modules\Admin\Models\SystemConfig;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\Platform\BrandSendDatas;
use Modules\Admin\Models\AccountDateStatModel;
use Modules\Admin\Models\SecUser;

class ReportService extends BaseApiService
{
    /**
     * 获取用户表当天数据
     * @return bool
     */
    public function getClentData()
    {

        $inputFileName = SystemConfig::query()->where('key','ClentDataPath')->value('value');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $data = $spreadsheet
            ->getSheet(0) // 指定第一个工作表为当前
            ->toArray();  // 转为数组

        $temp_data=[];
        $AccountKey = $this->getAccountKey(1);
        foreach ($data as $key => $value) {
            if ($key === 0) {
                $name = array_search('账户', $value);
                $collectioncost = array_search('消耗', $value);
                $ecpm = array_search('千次展现成本', $value);
                $adctr = array_search('点击率', $value);
                $ecpc = array_search('点击单价', $value);
                $adpv = array_search('展现量', $value);
                $click = array_search('点击量', $value);
                $returnoninvestment = array_search('投资回报率', $value);
                $takeordervolume = array_search('拍下订单量', $value);
                $transactionvolume = array_search('成交订单量', $value);
                $transactionamount = array_search('成交订单金额', $value);
                $alipaycost = array_search('订单成本', $value);
                $takeorderamount = array_search('拍下订单金额', $value);
                $pagearrive = array_search('转化数', $value);
                $convertcost = array_search('转化成本', $value);
                $addcartvolume = array_search('添加购物车量', $value);


            } else {
                $temp_data[$key - 1]['name'] = $value[$name];
                $temp_data[$key - 1]['collectioncost'] = $value[$collectioncost];
                $temp_data[$key - 1]['ecpm'] = $value[$ecpm];
                $temp_data[$key - 1]['adctr'] = $value[$adctr];
                $temp_data[$key - 1]['ecpc'] = $value[$ecpc];
                $temp_data[$key - 1]['adpv'] = $value[$adpv];
                $temp_data[$key - 1]['click'] = $value[$click];
                $temp_data[$key - 1]['returnoninvestment'] = $value[$returnoninvestment];
                $temp_data[$key - 1]['takeordervolume'] = $value[$takeordervolume];
                $temp_data[$key - 1]['transactionvolume'] = $value[$transactionvolume];
                $temp_data[$key - 1]['transactionamount'] = $value[$transactionamount];
                $temp_data[$key - 1]['alipaycost'] = $value[$alipaycost];
                $temp_data[$key - 1]['takeorderamount'] = $value[$takeorderamount];
                $temp_data[$key - 1]['pagearrive'] = $value[$pagearrive];
                $temp_data[$key - 1]['convertcost'] = $value[$convertcost];
                $temp_data[$key - 1]['addcartvolume'] = $value[$addcartvolume];
                !empty($value[$name]) && $temp_data[$key-1]['key'] = $AccountKey;
                $temp_data[$key - 1]['created_at'] = date('Y-m-d H:i:s');
//                BrandAccountReport::updateOrCreate(['name'=>$value[$name]] , $temp_data[$key - 1]);
            }

        }


        if (!empty($temp_data)){
            $res = BrandAccountReport::query()->insert($temp_data);
        }
        return true;
    }

    public function getPlanData()
    {

        $inputFileName = SystemConfig::query()->where('key','PlanDataPath')->value('value');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $data = $spreadsheet
            ->getSheet(0) // 指定第一个工作表为当前
            ->toArray();  // 转为数组

        $temp_data=[];
        $AccountKey = $this->getAccountKey(2);
        foreach ($data as $key => $value) {
            if ($key === 0) {
                $plan_name = array_search('计划名称', $value);
                $campaignName = array_search('计划组名称', $value);
                $name = array_search('计划', $value);
                $collectioncost = array_search('消耗', $value);
                $ecpm = array_search('千次展现成本', $value);
                $adctr = array_search('点击率', $value);
                $ecpc = array_search('点击单价', $value);
                $adpv = array_search('展现量', $value);
                $click = array_search('点击量', $value);
                $returnoninvestment = array_search('投资回报率', $value);
                $takeordervolume = array_search('拍下订单量', $value);
                $transactionvolume = array_search('成交订单量', $value);
                $transactionamount = array_search('成交订单金额', $value);
                $alipaycost = array_search('订单成本', $value);
                $takeorderamount = array_search('拍下订单金额', $value);
                $pagearrive = array_search('转化数', $value);
                $convertcost = array_search('转化成本', $value);
                $addcartvolume = array_search('添加购物车量', $value);
            } else {
                $temp_data[$key - 1]['plan_name'] = $value[$plan_name];
                $temp_data[$key - 1]['campaignName'] = $value[$campaignName];
                $temp_data[$key - 1]['name'] = $value[$name];
                $temp_data[$key - 1]['collectioncost'] = $value[$collectioncost];
                $temp_data[$key - 1]['ecpm'] = $value[$ecpm];
                $temp_data[$key - 1]['adctr'] = $value[$adctr];
                $temp_data[$key - 1]['ecpc'] = $value[$ecpc];
                $temp_data[$key - 1]['adpv'] = $value[$adpv];
                $temp_data[$key - 1]['click'] = $value[$click];
                $temp_data[$key - 1]['returnoninvestment'] = $value[$returnoninvestment];
                $temp_data[$key - 1]['takeordervolume'] = $value[$takeordervolume];
                $temp_data[$key - 1]['transactionvolume'] = $value[$transactionvolume];
                $temp_data[$key - 1]['transactionamount'] = $value[$transactionamount];
                $temp_data[$key - 1]['alipaycost'] = $value[$alipaycost];
                $temp_data[$key - 1]['takeorderamount'] = $value[$takeorderamount];
                $temp_data[$key - 1]['pagearrive'] = $value[$pagearrive];
                $temp_data[$key - 1]['convertcost'] = $value[$convertcost];
                $temp_data[$key - 1]['addcartvolume'] = $value[$addcartvolume];
                !empty($value[$name]) && $temp_data[$key-1]['key'] = $AccountKey;
//                BrandAccountReport::updateOrCreate(['name'=>$value[$name]] , $temp_data[$key - 1]);
                $temp_data[$key - 1]['created_at'] = date('Y-m-d H:i:s');
            }
        }


        if (!empty($temp_data)){
            $res = AccountPlanReport::query()->insert($temp_data);
        }
        return true;
    }

    /**
     * 用户表获取搜索key
     */
    public function getAccountKey($type)
    {
        if ($type==1){
            //1;
        }else{
            //
        }
        return md5(time());
    }

    /**
     * @desc       计划列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-16 17:40
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function planList(array $data)
    {
        $where = [];
        if(!empty($data['str'])){
            $where['whereIn'][] = ['name', $data['str']];
        }
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("account_plan_reports")->max('id');
            $advertise = DB::table('account_plan_reports')->where('id',$brand_id)->value('key_sign');

        }

        if(isset($data['media_id'])){
            $where['where'][] = ['media_id',$data['media_id']];
        }

        $list = AccountPlanReport::baseQuery($where)
            ->where('key_sign',$advertise)
            ->orderBy('collectioncost','desc')
            ->orderBy('returnoninvestment','desc')
            ->orderBy('created_at','desc')
            ->paginate($data['limit'])
            ->toArray();

        foreach ($list['data'] as &$info) {
            $this->dataFormat($info);
        }

        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @desc       报表汇总
     * @author     文明<736038880@qq.com>
     * @date       2022-07-16 18:00
     * @param array $data
     *
     * @return \Modules\Common\Services\JSON
     */
    public function accountSummary(array $data){


        $brandmodel = AccountSummaryReport::query();
        if(!empty($data['media_id'])){
            $brandmodel->where('media_id',$data['media_id']);
        }
        $info = $brandmodel->orderBy('id','desc')->where('date_day',date('Y-m-d',time()))->first();
        if(!empty($info)){
            $info = $info->toArray();
            $this->dataFormat($info);
        }else{
            $info = [];
        }
        return $this->apiSuccess('', ['info' => $info]);
    }

    /**
     * @desc       格式化字段数据
     * @author     文明<736038880@qq.com>
     * @date       2022-07-27 17:45
     * @param array $info
     */
    public function dataFormat(array &$info){
        if(isset($info['cost'])){
            $info['cost'] = number_format($info['cost']/100,2);
        }
        if(isset($info['collectioncost'])){
            $info['collectioncost'] = number_format($info['collectioncost']/100,2);
        }
        $info['ecpm'] = number_format($info['ecpm']/100,2);
        $info['adctr'] = number_format($info['adctr']*100,2)."%";
        $info['ecpc'] = number_format($info['ecpc']/100,2);
        $info['returnoninvestment'] = number_format($info['returnoninvestment']*100,2).'%';
        $info['transactionamount'] = number_format($info['transactionamount']/100,2);
        $info['alipaycost'] = number_format($info['alipaycost']/100,2);
        $info['takeorderamount'] = number_format($info['takeorderamount']/100,2);
        $info['convertcost'] = number_format($info['convertcost']/100,2);
        $info['addcartvolume'] = number_format($info['addcartvolume']);
        $info['pagearrive'] = number_format($info['pagearrive']);
        $info['transactionvolume'] = number_format($info['transactionvolume']);
        $info['takeordervolume'] = number_format($info['takeordervolume']);
        $info['click'] = number_format($info['click']);
        $info['adpv'] = number_format($info['adpv']);
        $info['media_name'] = $this->getMediaName($info['media_id']);
    }
    /**
     * @desc       计划搜索框内容
     * @author     文明<736038880@qq.com>
     * @date       2022-07-21 17:16
     * @return \Modules\Common\Services\JSON
     */
    public function planDropDown(array $params){
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("account_plan_reports")->max('id');
            $advertise = DB::table('account_plan_reports')->where('id',$brand_id)->value('key_sign');

        }
        $where = [
            'where' => [
                ['status', '=', 1],
                ['key_sign', '=', $advertise],
            ]
        ];
        if(!empty($params['search_value'])){
            $where['where'][] = ['name', 'like', "%{$params['search_value']}%"];
        }
        $list = AccountPlanReport::baseQuery($where)
            ->select(['name'])
            ->paginate(10)->toArray();

        $data = array_column($list['data'], 'name');

        return $this->apiSuccess('', ['list' => $data, 'total' => $list['total']]);
    }

    /**
     * @desc       获取计划推送列表
     * @author     文明<736038880@qq.com>
     * @date       2022-07-30 13:43
     * @param array $params
     *
     * @return \Modules\Common\Services\JSON
     */
    public function getPushList(array $params){
        $where = [];
        if(!empty($params['type'])){
            $where['where'][] = ['type', '=', $params['type']];
        }
        if(!empty($params['created_at'])){
            $where['between'][] = ['created_at', [$params['created_at'][0], $params['created_at'][1]]];
        }
        if(!empty($params['name'])){
            if($params['type'] == 1){
                $where['brand_name'][] = ['brand_name', 'like', "%{$params['name']}%"];
            }else{
                $where['plan_name'][] = ['plan_name', 'like', "%{$params['name']}%"];
            }
        }
        if(!empty($params['fie_name'])){
            $where['where'][] = ['fie_name', '=', $params['fie_name']];
        }

        $where['order'] = ['created_at' => 'desc', 'id' => 'desc'];

        $list = BrandSendDatas::baseQuery($where)
            ->paginate($params['limit'])->toArray();

        foreach ($list['data'] as &$val) {
            $val['type_name'] = $params['type'] == 1 ? $val['brand_name'] : $val['plan_name'];
        }

        return $this->apiSuccess('', ['list' => $list['data'], 'total' => $list['total']]);
    }

    /**
     * @Notes: 数据来源
     * @Author: 1900
     * @Date: 2022/8/16 10:07
     * @Interface getMediaName
     * @param $id
     * @return string
     */
    public function getMediaName($id)
    {
        $array = array(
            '103'=>"头条",
            '104'=>"广点通",
            '105'=>"快手",
        );
        return $array[$id]??'未知';
    }

    /**
     * @desc       获取统计列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-19 14:23
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatList(array $params){
        $where = [];
        if(!empty($params['team_name'])){
            $where['where'][] = ['sec_users.ext2', '=', $params['team_name']];
        }
        if(!empty($params['begin_time']) && !empty($params['end_time'])){
            $where['between'][] = ['account_date_stat.stat_date', [$params['begin_time'], $params['end_time']]];
        }

        if(!empty($params['platform_name'])){
            $where['where'][] = ['sec_users.util_name', '=', $params['platform_name']];
        }

//        if(!empty($params['account_name'])){
//            $where['where'][] = ['lv_account_date_stat.account_name', 'like', "%{$params['account_name']}%"];
//        }

        $where['order'] = ['account_date_stat.cost' => 'desc','sec_users.ext2' => 'desc','sec_users.platform_name' => 'desc','account_date_stat.created_at' => 'desc','account_date_stat.roi' => 'desc', 'account_date_stat.money' => 'desc'];

        $fields = 'lv_account_date_stat.id,lv_account_date_stat.advertiser_name,lv_sec_users.platform_name,lv_sec_users.util_name,lv_sec_users.goods,lv_sec_users.ext2,lv_sec_users.real_name,sum(lv_account_date_stat.cost) as cost,avg(lv_account_date_stat.roi) as roi,SUBSTRING_INDEX(group_concat(lv_account_date_stat.money order by lv_account_date_stat.id desc),",",1) as money,lv_account_date_stat.created_at,lv_account_date_stat.stat_date,lv_sec_users.ext';

        $where['group'] = ['account_date_stat.stat_date'];
        $groupLink = ['team_name' => 'sec_users.ext2', 'user_name' => 'sec_users.real_name', 'advertiser_name' => 'account_date_stat.advertiser_id', 'product_name' => 'sec_users.goods'];
        if(!empty($params['group_name']) && $params['group_name'] !== 'advertiser_name' && isset($groupLink[$params['group_name']])){
            $where['group'][] = $groupLink[$params['group_name']];
        }else{
            $where['group'][] = $groupLink['advertiser_name'];
        }

        $list = AccountDateStatModel::baseQuery($where)
            ->select(DB::raw($fields))
            ->join('sec_users', 'account_date_stat.advertiser_name','sec_users.sec_username')
            ->paginate($params['limit'])->toArray();

        return $this->apiSuccess('', ['list' => $list['data'], 'total' => $list['total']]);
    }

    /**
     * @desc       获取团队列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-22 10:29
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamList(){
        $teamList = SecUser::query()->groupBy('ext2')->pluck('ext2')->toArray();
        return $this->apiSuccess('', $teamList);
    }

}
