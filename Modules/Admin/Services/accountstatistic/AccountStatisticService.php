<?php
// +----------------------------------------------------------------------
// | Name: 咪乐多管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: 西安咪乐多软件 <997786358@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name 会员管理服务
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/29 14:53
 */

namespace Modules\Admin\Services\accountstatistic;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\AccountStatistic;
use Modules\Admin\Models\ZzTest;
use Modules\Admin\Services\BaseApiService;
use GuzzleHttp;

class AccountStatisticService extends BaseApiService
{

    protected $length = 2000;
    public function zts_statistics(array $data, string $uniqueStr = '')
    {
         $list = $this->requestASmartPitcher($data);
        $total = $list['total'];
        $list = $list['list'];
         $ids = array_column($list, 'advertiserId');
         $adIdLink = AccountStatistic::whereIn('advertiser_id',$ids)->pluck('id', 'advertiser_id')->toArray();
        $upsertData = [];
//            $body['data']['total']['list'];//当前账户的总消耗
            foreach($list as $v){
//                if($v['cost'] >'0.00') {
                    $arr = [];
                    $arr['cost'] = $v['cost'];
                    $arr['advertiser_name'] = $v['name'];
                    $arr['roi'] = $v['roi'];
                    $arr['type'] = 1;//自投手
                    $arr['money'] = $v['payAmount'];//消耗金额
                    $arr['remark'] = $v['remark'];
                    if(!empty($uniqueStr)){
                        $arr['unique_str'] = $uniqueStr;
                    }
                    if (isset($adIdLink[$v['advertiserId']])) {//update
                        $arr['id'] = $adIdLink[$v['advertiserId']];
                        //AccountStatistic::where('advertiser_id',$v['advertiserId'])->update($arr);
                    } else {//insert
                        $arr['id'] = '0';
                        $arr['advertiser_id'] = $v['advertiserId'];
                        $arr['ymd'] = date('Ymd',time());//自投手
                        $arr['account_id'] = $v['accountAdvertiserId'];
                    }
                $upsertData[] = $arr;
            }
            if(!empty($upsertData)){
                $upsertData = array_chunk($upsertData, 200);
                foreach ($upsertData as $val) {
                    AccountStatistic::upsert($val,['id']);
                }
            }
            if($total > $data['start'] + $this->length){
                $data['start'] = $data['start'] + $this->length;
                $this->zts_statistics($data, $uniqueStr);
            }
    }

    /**
     * @Notes: 获取各工具的cookie值
     * @Author: 1900
     * @Date: 2022/8/20 17:13
     * @Interface getVoucher
     * @param $type
     * @return mixed
     */
    public function getVoucher($type)
    {
        if($type == 1){
            $key = 'zts_cookie';
        }
        $info = DB::table('system_configs')->where('key',$key)->get()->first();
        $data['header'] = json_decode($info->value,true);
        $data['url']  = $info->info;
        return $data;
    }
    /**
     * @Notes: 智投手发起请求
     * @Author: 1900
     * @Date: 2022/8/20 16:41
     * @Interface requestASmartPitcher
     * @param $arr
     * @return mixed
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function requestASmartPitcher($arr)
    {
        $header = $this->getVoucher(1);
        $url = $header['url']??"https://zhitou.zhishangsoft.com/admin/ad-advertiser/query";
        $http = new GuzzleHttp\Client([
            'verify' => false
        ]);
        $response = $http->get($url, [
            'headers' => $header['header'],
            'query' => $this->getData($arr),
        ]);
        $body = $response->getBody()->getContents();
        $this->log('requestASmartPitcher', $body);
        $body = json_decode($body,true);
        if($body && $body['code'] == 1 && $body['success'] == true){
            return $body['data']['page'];
        }else{
            return $body;
        }
    }

    public function getData($arr)
    {
        $data['length'] = "2000";
        $data['pageNo'] = "1";
        $data['pageSize'] = "2000";
        $data['orderField'] = "cost";
        $data['orderType'] = "esc";
        $data['startDate'] = $arr['startDate'];
        $data['endDate'] = $arr['endDate'];
        $data['start'] =  $arr['start'];
        return $data;

    }

}
