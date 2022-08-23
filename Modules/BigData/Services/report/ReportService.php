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
namespace Modules\BigData\Services\report;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\BigData\Models\BrandDataReport;
use Modules\BigData\Models\BrandAccountReport;
use Modules\BigData\Models\BrandAlertSetting;
use Modules\Admin\Models\AccountSummaryReport;
use Modules\BigData\Services\BaseApiService;
//use Illuminate\Support\Facades\Redis;
class ReportService extends BaseApiService
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
            $reportmodel = BrandAccountReport::query();
            $reportmodel = $this->queryCondition($reportmodel,$data,'name');
            if (isset($data['name'])){
                $reportmodel = $reportmodel->where('name','like','%'.$data['name'].'%');
            }
            if(isset($data['str']) && !empty($data['str'])){
                if(!is_array($data['str'])){
                    $data['str'] = explode(',',$data['str']);
                }
                $reportmodel =  $reportmodel->whereIn('name',$data['str']);
            }
             $advertise = '';
            if(!$advertise){
                $brand_id = DB::table("brand_account_reports")->max('id');
                $advertise = DB::table('brand_account_reports')->where('id',$brand_id)->value('key_sign');
            }
            if(isset($data['media_id'])){
                $reportmodel->where('media_id',$data['media_id']);
            }
            $reportmodellist = $reportmodel->where('key_sign',$advertise)
                ->orderBy('id','asc')
                ->paginate($data['limit'])
                ->toArray();
            foreach($reportmodellist['data'] as &$v){
                $this->formdata($v);
            }

            return $this->apiSuccess('',[
                'list'=>$reportmodellist['data'],
                'total'=>$reportmodellist['total']
            ]);
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

    public function formdata(array &$v)
    {
        if(isset($v['cost'])){
            $v['cost'] = number_format($v['cost']/100,2);
        }
        if(isset($v['collectioncost'])){
            $v['collectioncost'] = number_format($v['collectioncost']/100,2);
        }
        $v['adctr'] = number_format(($v['adctr']*100),2).'%';
        $v['returnoninvestment'] = number_format(($v['returnoninvestment']*100),2).'%';
        $v['ecpm'] = number_format($v['ecpm']/100,2);
        $v['ecpc'] = number_format($v['ecpc']/100,2);
        $v['adpv'] = number_format($v['adpv']);
        $v['click'] = number_format($v['click']);
        $v['takeordervolume'] = number_format($v['takeordervolume']);
        $v['transactionvolume'] = number_format($v['transactionvolume']);
        $v['transactionamount'] = number_format($v['transactionamount']/100,2);
        $v['alipaycost'] = number_format($v['alipaycost']/100,2);
        $v['takeorderamount'] = number_format($v['takeorderamount']/100,2);
        $v['pagearrive'] = number_format($v['pagearrive']);
        $v['convertcost'] = number_format($v['convertcost']/100,2);
        $v['addcartvolume'] = number_format($v['addcartvolume']);
        $v['media_name'] = $this->getMediaName($v['media_id']);
    }
    /*
    *汇总查询
    */
    public function Summary(array $data){

        $brandmodel = AccountSummaryReport::query();
        if(!empty($data['media_id'])){
            $brandmodel->where('media_id',$data['media_id']);
        }
        $list = $brandmodel->orderBy('id','desc')->where('date_day',date('Y-m-d',time()))
            ->first()->Toarray();
        $this->formdata($list);
            return $this->apiSuccess('',[
                'info'=>$list,
            ]);
    }
    /**
     * @name 下拉框数据
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function dropDown(int $media_id){
        $dorpdown = BrandAccountReport::query();
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("brand_account_reports")->max('id');
            $advertise = DB::table('brand_account_reports')->where('id',$brand_id)->value('key_sign');
        }
        if(!empty($media_id)){
            $dorpdown->where("media_id",$media_id);
        }
        $dorpdown = $dorpdown->where('status',1)
            ->where('key_sign',$advertise)
            ->groupBy('name')
            ->get(['name'])->toArray();
        $dorpdown = array_column($dorpdown,'name');
        return $this->apiSuccess('',$dorpdown);
    }

    /**
     * @name 報警規則設置
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function AlertSetting(array $data){
        $dorpdown = BrandAlertSetting::where(['fie_name'=>$data['field_name']])->first();
        if($dorpdown){
            BrandAlertSetting::where('id', $dorpdown->id)->update(['name' => $data['name'],'fie_name'=>$data['field_name'],'values'=>$data['values'],'updated_at'=> Carbon::now()]);
        }else{
            $BrandAlertSetting = new BrandAlertSetting;
            $BrandAlertSetting->name = $data['name'];
            $BrandAlertSetting->fie_name = $data['field_name'];
            $BrandAlertSetting->values = $data['values'];
            $BrandAlertSetting->save();
        }
        return $this->apiSuccess('',[]);
    }

    /**
     * @name 報警展示
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function SettingList(){
        $dorpdown = BrandAlertSetting::query();
        $list = $dorpdown->where('state',1)
            ->get(['name','fie_name','values'])
            ->toArray();
        return $this->apiSuccess('',$list);
    }



    /**
     * @name 添加
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:29
     * @method  POST
     * @param  data Array 添加数据
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.password String 项目地址
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function store(array $data)
    {
        return $this->commonCreate(BrandDataReport::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function edit(int $id){
        return $this->apiSuccess('',BrandDataReport::find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 会员id
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(BrandDataReport::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 会员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(BrandDataReport::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 会员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function delete(int $id){
        return $this->commonDestroy(BrandDataReport::query(),[$id]);

    }
}
