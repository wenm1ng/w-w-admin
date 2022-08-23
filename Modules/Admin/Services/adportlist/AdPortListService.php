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

namespace Modules\Admin\Services\adportlist;


use Carbon\Carbon;
use Modules\BigData\Models\BrandAccountReport;
use Modules\Admin\Models\OncesAdvertiser;
use Modules\Admin\Models\Platform\AuthThird;
use Modules\Admin\Models\AccountPlanReport;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\platform\AuthService;
use Modules\Common\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
class AdPortListService extends BaseApiService
{
    /**
     * @name 列表
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.nickname String 昵称
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.created_at Array 创建时间
     * @param  data.updated_at Array 更新时间
     * @return JSON
     **/
    public function index(array $data)
    {
        $model = OncesAdvertiser::query();
        $model = $this->queryCondition($model,$data,'name');
        if (isset($data['name'])){
            $model = $model->where('name','like','%'.$data['name'].'%');
        }
        if (isset($data['plat_type']) && $data['plat_type'] >0){
            $model = $model->where('plat_type',$data['plat_type']);
        }
        if (isset($data['status']) && $data['status'] >0){
            $model = $model->where('status',$data['status']);
        }
        $list = $model->orderBy('id','asc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }


    /**
     * @Notes: 获取纵横组织下资产账户列表
     * @Author: 1900
     * @Date: 2022/8/13 9:33
     * @Interface accountlist
     * @param int $id
     */
    public function accountlist(int $id){

        $url = 'https://ad.oceanengine.com/open_api/2/majordomo/advertiser/select/';
        $authinfo = AuthThird::where('account_id','1740831698928651')->get()->first();
        $header['Access-Token'] = $authinfo->access_token;
        $header['X-Debug-Mode'] = 1;
        $data['advertiser_id'] = $authinfo->account_id;
        $arr['header'] = $header;
        $arr['data'] = $data;
        $arr['url'] = $url;
        $portlist =  (new PortService())->GetAccountList($arr);
        if(isset($portlist) && $portlist['message'] == 'OK'){
            //var_dump($portlist);die;
           foreach($portlist['data']['list'] as $k=>$v){
               $info = OncesAdvertiser::where('advertiser_id',$v['advertiser_id'])->first();
               if(!isset($info)){
                   OncesAdvertiser::create([
                       'advertiser_id'=>$v['advertiser_id'],
                       'access_token'=>$authinfo->access_token,
                       'account_id'=>$authinfo->account_id,
                       'third_id'=>$authinfo->id,
                       'advertiser_name'=>$v['advertiser_name'],
                       'created_at'=>Carbon::now()
                   ]);
               }else{
                   OncesAdvertiser::where('advertiser_id',$v['advertiser_id'])->update([
                       'access_token'=>$authinfo->access_token,
                       'account_id'=>$authinfo->account_id,
                       'third_id'=>$authinfo->id,
                       'advertiser_name'=>$v['advertiser_name'],
                       'updated_at'=>Carbon::now()
                   ]);
               }
           }
        }

        echo 343;die;
    }

    /*
     *
     *实时获取广告计划数据
     */
    public function getPlanReportList()
    {

        $url = 'https://ad.oceanengine.com/open_api/2/report/ad/get/';
        $type = "AccountPlanReport";
        $data['start_date'] = date("Y-m-d",time());
        $data['end_date'] = date("Y-m-d",time());
        $this->issueRequest($url,$type,$data);
       echo 123;die;
        //如果token失效,则刷新token
        //AuthService::authFreshToken($accountId);
    }

    /*
     *
     *实时获取广告主数据
     */
    public function getUserReportList()
    {
        $url = 'https://ad.oceanengine.com/open_api/2/report/advertiser/get/';
        $type = "BrandAccountReport";
        $data['start_date'] = date("Y-m-d",time());
        $data['end_date'] = date("Y-m-d",time());
        $this->issueRequest($url,$type,$data);
        echo 123;die;
        //如果token失效,则刷新token
    }

    /*
     * 获取用户的余额数据
     * advertiser/fund/get
     */
    public function getUsersMake()
    {
        $url = 'https://ad.oceanengine.com/open_api/2/advertiser/fund/get/';
        $type = "OncesAdvertisers";
        $this->issueRequest($url,$type);


    }

    /*
     * 发起请求
     */
    public function issueRequest($url,$type,$data=[])
    {
        $accountId = "1731426525352963";
        (new AuthService())->authFreshToken($accountId);
        $info = OncesAdvertiser::where('state','1')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $platinfo = DB::table("platform")->where("id",1)->first();
        $uuids = Uuid::uuid1();
        $str = $uuids->getHex()->toString();
        foreach($info as $k=>$v){
            $header['Access-Token'] = $v->third->access_token;
            $header['X-Debug-Mode'] = 1;
            $data['advertiser_id'] = $v->advertiser_id;
//           $data['fields'] = ['cost','show','avg_show_cost','click','avg_click_cost','ctr','convert','convert_cost','convert_rate'];
            $arr['header'] = $header;
            $arr['data'] = $data;
            $arr['url'] = $url;
            $portlist =  (new PortService())->GetAccountList($arr);
            if(isset($portlist) && $portlist['message'] == 'OK'){
                $user['advertiser_id'] = $v->advertiser_id;
                $user['advertiser_name'] = $v->advertiser_name;
                $user['plat_id'] = $platinfo->id;
                $user['plat_name'] = $platinfo->platform_name;
                $user['str'] = $str;
                if($type == 'BrandAccountReport'){
                    $this->getUserList($portlist['data']['list'],$user);
                }elseif($type == "AccountPlanReport"){
                    $this->getjhList($portlist['data']['list'],$user);
                }elseif($type == "OncesAdvertisers"){
                    $this->getUsersMakeList($portlist['data']);
                }
            }else{
                var_dump($portlist);
            }
        }
        echo 123;die;
        //如果token失效,则刷新token
    }

    //广告计划数据
    public function getjhList($data,$user)
    {
        foreach($data as $k=>$v){
            //var_dump($v);die;
//            if(isset($v['ad_id'])){//计划id必须存在,
            $info = AccountPlanReport::where('plan_id',$v['ad_id'])->whereDate('created_at',date("Y-m-d"))->first();
//            $info = AccountPlanReport::where('memberid',$v['advertiser_id'])->whereDate('created_at',date("Y-m-d"))->first();
            //var_dump($info);die;
                $arr['adctr'] = $v['ctr'];
                $arr['ecpc'] = $v['avg_click_cost'];
                $arr['ecpm'] = $v['avg_show_cost'];
                $arr['click'] = $v['click'];
                $arr['adpv'] = $v['show'];
                $arr['convertcost'] = $v['convert_cost'];
                $arr['pagearrive'] = $v['convert'];
                $arr['memberid'] =$user['advertiser_id'];
                $arr['name'] =$user['advertiser_name'];
                $arr['collectioncost'] = $v['cost'];
                $arr['campaignId'] = $v['campaign_id']??'0';
                $arr['campaignName'] = $v['campaign_name']??"";
                $arr['plat_name'] =$user['plat_name'];
                $arr['plat_id'] =$user['plat_id'];
                $arr['key_sign'] =$user['str'];
                if(!isset($info)){
                    $arr['created_at'] = Carbon::parse($v['stat_datetime'])->toDateTimeString();
                    $arr['plan_id'] = $v['ad_id']??"0";
                    $arr['plan_name'] = $v['ad_name']??'';
                   $info =  AccountPlanReport::create($arr);
                }else{
                    $arr['updated_at'] = Carbon::parse($v['stat_datetime'])->toDateTimeString();
//                    AccountPlanReport::where('plan_id',$v['ad_id'])->update($arr);
                    AccountPlanReport::where('memberid',$v['advertiser_id'])->update($arr);
                }
            }
//        }
    }

    //广告主数据
    public function getUserList($data,$user)
    {
        foreach($data as $k=>$v){
            $info = BrandAccountReport::where('memberid',$v['advertiser_id'])->whereDate('created_at',date("Y-m-d"))->first();
                $arr['adctr'] = $v['ctr'];
                $arr['ecpc'] = $v['avg_click_cost'];
                $arr['ecpm'] = $v['avg_show_cost'];
                $arr['collectioncost'] = $v['cost'];
                $arr['adpv'] = $v['show'];
                $arr['convertcost'] = $v['convert_cost'];
                $arr['pagearrive'] = $v['convert'];
                $arr['created_at'] = Carbon::parse($v['stat_datetime'])->toDateTimeString();
                $arr['click'] = $v['click'];
                $arr['name'] =$user['advertiser_name'];
                $arr['plat_name'] =$user['plat_name'];
                $arr['plat_id'] =$user['plat_id'];
            if(!isset($info)){
                $arr['created_at'] = Carbon::parse($v['stat_datetime'])->toDateTimeString();
                $arr['memberid'] =$user['advertiser_id'];
                $info =  BrandAccountReport::create($arr);
            }else{
                $arr['updated_at'] = Carbon::parse($v['stat_datetime'])->toDateTimeString();
//                    BrandAccountReport::where('plan_id',$v['ad_id'])->update($arr);
                BrandAccountReport::where('memberid',$v['advertiser_id'])->update($arr);
            }
        }
    }


    /**
     * @Notes:
     * @Author: 1900
     * @Date: 2022/8/12 17:39
     * @Interface getUsersMakeList
     * @param array $data
     */
    public function getUsersMakeList(array $data)
    {
        if(isset($data['advertiser_id'])){
            $arr['email'] = $data['email'];
            $arr['balance'] = $data['balance'];
            $arr['valid_balance'] = $data['valid_balance'];
            $arr['cash'] = $data['cash'];
            $arr['valid_cash'] = $data['valid_cash'];
            $arr['updated_at'] = Carbon::now();
            OncesAdvertiser::where('advertiser_id',$data['advertiser_id'])->update($arr);
        }
    }

}
