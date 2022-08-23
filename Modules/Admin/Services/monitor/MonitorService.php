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

namespace Modules\Admin\Services\monitor;


use Carbon\Carbon;
use Modules\Admin\Models\MonitorSummaryList;
use Modules\Admin\Models\MonitorSummarySetting;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Platform\Platform;
class MonitorService extends BaseApiService
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
        $model = MonitorSummaryList::query();
//        var_dump($data);die;
        $model = $this->queryCondition($model,$data,'name');
        if (isset($data['name'])){
            $model = $model->where('name','like','%'.$data['name'].'%');
        }
        if (isset($data['plat_name'])){
            $model = $model->where('plat_name',$data['plat_name']);
        }
         if (isset($data['campaignname'])){
             $model = $model->where('campaignname',$data['campaignname']);
         }
        if (isset($data['plan_name'])){
            $model = $model->where('plan_name',$data['plan_name']);
        }
        if (isset($data['status'])){
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

    /*
     * 平台下拉框接口
     */
    public function platList()
    {
        $info = Platform::where('is_delete',0)->get(["platform_name as name"])->Toarray();
        $dorpdown = array_column($info,'name');
        return $this->apiSuccess('',$dorpdown);
    }

    /**
     * @name 账号下拉框数据
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function dropDown(){

        $dorpdown = MonitorSummaryList::query();
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("monitor_summary_lists")->max('id');
            $advertise = DB::table('monitor_summary_lists')->where('id',$brand_id)->value('key_sign');
            if ($advertise){
                $dorpdown->where('key_sign',$advertise);
            }
        }
        $dorpdown = $dorpdown->where('status',1)
            ->groupBy('name')
            ->get(['name'])->toArray();
        //var_dump($dorpdown);die;
        $dorpdown = array_column($dorpdown,'name');
        return $this->apiSuccess('',$dorpdown);
    }


    /**
     * @name 计划组下拉框数据
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function dropDown_Plan_Group(array $data){

        //var_dump($name);die;
        $dorpdown = MonitorSummaryList::query();
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("monitor_summary_lists")->max('id');
            $advertise = DB::table('monitor_summary_lists')->where('id',$brand_id)->value('key_sign');
            if ($advertise){
                $dorpdown->where('key_sign',$advertise);
            }
        }
        if(isset($data['name'])){
            $dorpdown->where('name',$data['name']);
        }
        $dorpdown = $dorpdown->where('status',1)
            ->groupBy('campaignname')
            ->get(['campaignname'])->toArray();
        //var_dump($dorpdown);die;
        $dorpdown = array_column($dorpdown,'campaignname');
        return $this->apiSuccess('',$dorpdown);
    }

    /**
     * @name 计划 下拉框数据
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function dropDown_Plan(array $data){

        //var_dump($data);die;
        $dorpdown = MonitorSummaryList::query();
        $advertise = '';
        if(!$advertise){
            $brand_id = DB::table("monitor_summary_lists")->max('id');
            $advertise = DB::table('monitor_summary_lists')->where('id',$brand_id)->value('key_sign');
            if ($advertise){
                $dorpdown->where('key_sign',$advertise);
            }
        }
        if (isset($data['name'])){
            $dorpdown->where('name',$data['name']);
        }
        if (isset($data['campaignname'])){
            $dorpdown->where('campaignname',$data['campaignname']);
        }

        $dorpdown = $dorpdown->where('status',1)
            ->groupBy('plan_name')
            ->get(['plan_name'])->toArray();
        //var_dump($dorpdown);die;
        $dorpdown = array_column($dorpdown,'plan_name');
        return $this->apiSuccess('',$dorpdown);
    }


    /*
     * 报警规则展示
     */
    public function rules(array $data)
    {
        $model= MonitorSummarySetting::query();
        $model = $this->queryCondition($model,$data,'name');
        if(isset($data['name'])){
            $model = $model->where('name','like','%'.$data['name'].'%');
        }

        if(isset($data['group_id'])){
            $model = $model->where('group_id','like','%'.$data['group_id'].'%');
        }
        if (isset($data['status'])){
            $model = $model->where('status',$data['status']);
        }

        $list = $model->orderBy('id','asc')
            ->paginate($data['limit'])
            ->toArray();
        $data = $list['data'];
        foreach($data as $k=>$v){
            $data[$k]['key_sign'] = $this->monitorEncrypt((int)$v['key_sign']);
        }
        return $this->apiSuccess('',[
            'list'=>$data,
            'total'=>$list['total']
        ]);
    }


    /*
     * 报警规则设置
     */
    public function rulesedit(int $id)
    {
        $info = MonitorSummarySetting::find($id)->toArray();
            $info['key_sign'] = $this->monitorEncrypt((int)$info['key_sign']);

        return $this->apiSuccess('',$info);
    }

    /*
     * 报警规则提交
     */
    public function rulesupdate(int $id,array $data)
    {
        $arr = $data['key_sign'];
        //开关加密
        $sum = $this->monitorDecrypt($arr);
        //var_dump($sum);die;
        $data['key_sign'] = $sum;

        $data['returnoninvestment'] = trim($data['returnoninvestment'],"%");

        return $this->commonUpdate(MonitorSummarySetting::query(),$id,$data);

    }

    /*
     * 状态修改rulesSwitch
     */
    public function rulesstatus(int $id,array $data)
    {

        return $this->commonStatusUpdate(MonitorSummarySetting::query(),$id,$data);

    }

    /*
     * 开关修改 rulesSwitch
     */
    public function rulesSwitch(int $id,array $data)
    {
        $arr = $data['key_sign'];
        $sum = $this->monitorDecrypt($arr);
        //$sums = monitorEncrypt($sum);
        $data['key_sign'] = $sum;
        $data['updated_at'] = Carbon::now();
        return $this->commonStatusUpdate(MonitorSummarySetting::query(),$id,$data);

    }
    /*
     * 规则删除
     */
    public function rulesdelete(int $id)
    {
        return $this->commonDestroy(MonitorSummarySetting::query(),[$id]);

    }
    /*
     * 规则添加
     */
    public function rulesAdd(array $data)
    {
            $arr = $data['key_sign'];
        $sum = $this->monitorDecrypt($arr);
        //$sums = monitorEncrypt($sum);
        $data['key_sign'] = $sum;
        $data['returnoninvestment'] = trim($data['returnoninvestment'],"%");

        return $this->commonCreate(MonitorSummarySetting::query(),$data);

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
        return $this->commonCreate(Store::query(),$data);
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
        return $this->apiSuccess('',Store::find($id)->toArray());
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
        return $this->commonUpdate(Store::query(),$id,$data);
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
        return $this->commonStatusUpdate(Store::query(),$id,$data);
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
        return $this->commonDestroy(Store::query(),[$id]);

    }

    /*
 * 报警规则设置
 * 判断有几个规则被开启了,几个规则被关闭了,
 */
    function monitorDecrypt($data){
        $sum = 0;
        for($i=0;$i<count($data);$i++){
            if($data[$i] == 1){
                $sum += pow(2,$i);
            }
        }
        return $sum;
    }

    /*
     * 数据库取出,计算出规则开启序列
     * 多选解密
     */
    function monitorEncrypt($str,&$data = [0,0,0,0,0]){
        //
        $n = (int)log($str,2);
        if($n >= 0){
            $data[$n] = 1;
            $m = $str - pow(2,$n);
            if($m){
                $this->monitorEncrypt($m,$data);
            }
        }

        //print_r($data);
        return  $data;

    }
}
