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

namespace Modules\BigData\Services\sendmessage;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\BigData\Services\BaseApiService;
use GuzzleHttp;
use Modules\BigData\Models\BrandDataReport;
use Modules\BigData\Models\BrandAccountReport;
use Modules\BigData\Models\BrandAlertSetting;
use Modules\Admin\Models\AccountSummaryReport;
use Modules\Admin\Models\AccountPlanReport;
class SendMessagePlanService extends BaseApiService
{

    public function __construct()
    {
        return;
    }

    public function robot()
    {
        $array = array(
            ['name'=>'机器人1', 'key'=>'jq1', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=3326bc300d1c0ba0a2b7e0426ecdb2c50e72c4c3e2a8934a5bbb56330db0eb09'],
            ['name'=>'机器人2', 'key'=>'jq2', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=60dd87b0960553a2aada2e3d68361383a6a94374452e538f4531f4dc5c11a4b1'],
            ['name'=>'机器人3', 'key'=>'jq3', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=51c0fd9b68ed4bd6b64123449c62e77083f11727f398ecb0e495bdc5422cd301'],
            ['name'=>'机器人4', 'key'=>'jq4', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=96af3f86b2e466e17cd7849e5cb62eda304719e909b4c9d54e3b68cc3fea87ed'],
            ['name'=>'机器人5', 'key'=>'jq5', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=2fcd40d0dba3033955fa363bf5b9aa17d2bcb1d6481ac5aed29ae87f1f01d349'],
            ['name'=>'机器人6', 'key'=>'jq6', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=56472319745533f0b76f8305167a0362cfda38a05d353b29a11c5849799fd441'],
            ['name'=>'机器人7', 'key'=>'jq7', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=43d4382f165eb3a14b2c7d37f593e6a7db5f0f85516a9a5ec4b7e553c6a108db'],
            ['name'=>'机器人8', 'key'=>'jq8', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=4ad1e4f70f667d5ebcf67537033c7eb028f20caf60f7a91e210422c90b74cf3c'],
            ['name'=>'机器人9', 'key'=>'jq9', 'url'=>'https://oapi.dingtalk.com/robot/send?access_token=f5133418562e93d5955aca39a434f93ac1180ab2e603f6fb7f361377a4ba3ddf'],
        );
        return $array[rand(0,8)];
    }

    public function userphone()
    {

//        $array = array('13538116492','18664335586');
        $array = array('18672794389','18672794389');

        return $array[rand(0,1)];

    }

    public function getMediaName($id)
    {
        $array = array(
            '103'=>"头条",
            '104'=>"广点通",
            '105'=>"快手",
        );
        return $array[$id]??'未知';
    }

    //计划设置     钉钉发送消息
    public function send_dd($info,$arr,$keys){
        //var_dump($keys);die;
        $jqr = $this->robot();
        $phone = '@'.$this->userphone();
        $media_name = "平台:  ".$this->getMediaName($info->media_id).PHP_EOL;
        $user = '账户: '.$info->name.PHP_EOL;
        $campaignName = '计划组: '.$info->campaignName.PHP_EOL;//计划组
        $plan_name = '计划: '.$info->plan_name.PHP_EOL;//计划
        $sign = '';
        if($arr->fie_name == 'returnoninvestment'){ $sign = "(低于预警值)";}//投资回报率
        if($arr->fie_name == 'collectioncost'){ $sign = "(高于预警值)";}//投资回报率

        $remark = $arr->name.": [".$info->$keys."]   ".$sign.PHP_EOL."请及时处理".$phone;
        $message=$media_name.$user.$campaignName.$plan_name.$remark;
        $data = array ('msgtype' => 'text','text' => array ('content' => $message));
        $http = new GuzzleHttp\Client;
        $response = $http->post($jqr['url'], [
            'headers' => ['Content-Type' => 'application/json;charset=utf-8'],
            'json' => $data,
        ]);
        $body = $response->getBody()->getContents();
        $body = json_decode($body,true);
        if($body['errmsg'] == 'ok'){
            // 通知成功,插入
            DB::table('brand_send_datas')->insert([
                'brand_id' => $info->id,
                'name' => $arr->name,
                'brand_name' => $info->name,
                'plan_name' => $info->plan_name,
                'campaignName' => $info->campaignName,
                'fie_name' => $arr->fie_name,
                'values' => $info->$keys,
                'type' => 2,
                'keys' => $info->key_sign,
                'keyword' =>md5($info->memberid.$info->campaignId.$info->plan_id.$info->$keys),
                'created_at' => Carbon::now()
            ]);
            DB::table('account_plan_reports')->where('id',$info->id)->update(['send_type' => 1,'updated_at' => Carbon::now()]);
        }
    }
    /*
    * $key : 预警数据
    * $res : 预警规则
    */
    public function compare_Early($info,$res,$keys)
    {
            $arr = DB::table("brand_send_datas")->where("brand_id",$info->id)->where('fie_name',$keys)->get();
            DB::table('brand_account_reports')->where('id',$info->id)->update(['type' => 1,'updated_at' => Carbon::now()]);
            if(!$arr->all()){
                $this->send_dd($info,$res,$keys);
            }
    }

    /**
     * @Notes:新版本的预警
     * @Author: 1900
     * @Date: 2022/8/16 15:19
     * @Interface AnewEarly
     */
    public function AnewEarly()
    {
        //投资回报率
        $returnoninvestment = DB::table('brand_alert_settings')->select('name','fie_name','values','id')
            ->where('fie_name', 'returnoninvestment')->first();
        //消耗
        $collectioncost = DB::table('brand_alert_settings')->select('name','fie_name','values','id')
            ->where('fie_name', 'collectioncost')->first();

        $reportmodel = AccountPlanReport::query();
        $advertise = '';
        if (!$advertise) {
            $brand_id = DB::table("account_plan_reports")->max('id');
            $advertise = DB::table('account_plan_reports')->where('id', $brand_id)->value('key_sign');
            $reportmodel = $reportmodel->where('key_sign', $advertise)->where('returnoninvestment','>','0.00');
        }
        if (isset($returnoninvestment) && isset($collectioncost)) {
            $reportmodel->where(function ($query) use ($returnoninvestment, $collectioncost) {
                $query->where('returnoninvestment', '<', $returnoninvestment->values)
                    ->orWhere('collectioncost', '>', $collectioncost->values * 100);
            });
        }
        $reportmodel = $reportmodel->where("send_type",0);
        //符合条件的数据.
        $reportmodel = $reportmodel
            ->select('media_id','name','campaignName','plan_name','id','key_sign','memberid','campaignId','plan_id','returnoninvestment','collectioncost')
            ->groupBy('id')->get();
        foreach($reportmodel as $k=>$v){
            $this->compareData($v,$returnoninvestment,$collectioncost);
        }
    }

    /**
     * @Notes:
     * @Author: 1900
     * @Date: 2022/8/16 14:44
     * @Interface compareData
     * @param $info
     * @param $retu
     * @param $coll
     */
    public function compareData($info,$retu,$coll)
    {
        if ($retu->values > $info->returnoninvestment) {
            //投资回报率
            $keyword = md5($info->memberid . $info->campaignId . $info->plan_id . $info->returnoninvestment);
            $keyword_info = DB::table("brand_send_datas")->where('keyword', $keyword)->where('type', 2)->first();
            $keys = $retu->fie_name;
            if (!isset($keyword_info)) {
                $this->compare_Early($info, $retu, $keys);
            }
        }
        if ($coll->values > $info->collectioncost) {
            //消耗
            $keyword1 = md5($info->memberid . $info->campaignId . $info->plan_id . $info->collectioncost);
            $collectioncost_info = DB::table("brand_send_datas")->where('keyword', $keyword1)->where('type', 2)->first();
            $keys = $coll->fie_name;
            if (!isset($collectioncost_info)) {
                $this->compare_Early($info, $coll, $keys);
            }
        }
    }
}
