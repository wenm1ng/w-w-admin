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
namespace Modules\Event\Services\updateevent;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Redis;
use Modules\Event\Models\BwdEvent;
use Modules\Event\Models\BwdSubEvent;
use Modules\Event\Services\BaseApiService;
use Modules\Admin\Models\AuthAdmin;
use Modules\Event\Models\BwdLog;
use Modules\Event\Models\BwdChangeRecord;
class UpdateEventService extends BaseApiService
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
    public function edit(int $id)
    {
        return $this->apiSuccess('',BwdEvent::find($id)->toArray());

    }

    //
    public function bwdFlag($flag,$type=1)
    {
        $opacData = array(
            'bwd_sh' => array(
                'title' => '审核',
                'status' => 10,
                'next' => 40,
                'triggerStatus' => array(10)
            ),
            'bwd_js' => array(
                'title' => '接受任务',
                'status' => 40,
                'next' => 100,
                'triggerStatus' => array(40)
            ),
            'bwd_tj' => array(
                'title' => '提交任务',
                'status' => 100,
                'next' => 150,
                'triggerStatus' => array(100)
            ),
            'bwd_pj' => array(
                'title' => '评价任务',
                'status' => 150,
                'next' => 200,
                'triggerStatus' => array(150)
            ),
            'bwd_cx' => array( //撤销之后实例就直接终止了
                'title' => '撤销任务',
                'status' => '',
                'next' => 500,
                'triggerStatus' => array(10,40,100,150,200)
            ),
            'bwd_jj' => array(
                'title' => '拒绝任务',
                'status' => '',
                'next' => 501,
                'triggerStatus' => array(10)
            ),
            'bwd_zj' => array(
                'title' => '转交任务',
                'status' => '',
                'next' => 40,
                'triggerStatus' => array(100)
            ),
            'bwd_ht' => array(
                'title' => '回退任务',
                'status' => '',
                'next' => 40,
                'triggerStatus' => array(40)
            ),
        );
        if($type ==1){
            return $opacData[$flag];
        }
        return $opacData;
    }

    //当前操作人
    public function at_present($data)
    {
        // 获取当前通过认证的用户...
        //当前操作人
        $arr = [];
        $user = Auth::user();
        $arr['present_id'] = $user->id;
        $arr['present_name'] = $user->name;
        //审核与转交的时候设计师需要修改,其余时候不需要
        $bwdinfo = BwdEvent::find($data['id']);
        $arr['bwd_designerid'] = $bwdinfo->bwd_designerid;
        $arr['bwd_designer'] = $bwdinfo->bwd_designer;
        //下一步操作人  bwd_sh 审核  转交任务 bwd_zj  回退任务,回退到哪一步
        if(in_array($data['bwd_flag'],array('bwd_zj','bwd_ht'))){//
            $nextname = AuthAdmin::where('id',$data['bwd_designerid'])->value('name');
            $arr['bwd_nextid'] = $data['bwd_designerid'];//下一步操作人
            $arr['bwd_nextname'] = $nextname;

            //转交给别人
            if(in_array($data['bwd_flag'],array('bwd_zj'))) {//
                $arr['bwd_designerid'] = $data['bwd_designerid'];//设计师
                $arr['bwd_designer'] = $nextname;
            }
        }
        //审核单独设置,
        if(in_array($data['bwd_flag'],array('bwd_sh'))){
            //判断单行还是串行.
            foreach($data['task'] as $k=>$v){
                if($data['type'] == 1 && $k > 0){
                    $subdata['state'] = 2;
                }
                $subname = AuthAdmin::where('id',$v['bwd_designerid'])->value('name');
                $subdata['bwdid'] = $data['id'];
                $subdata['bwd_designer'] = $subname;
                $subdata['bwd_designerid'] = $v['bwd_designerid'];
                $subdata['bwd_status'] = '40';
                $subdata['taskname'] = $v['taskname'];
                $subdata['content'] = $v['content'];
                $subdata['type'] = $data['type'];
                $subdata['sort'] = $k;
                $subdata['created_at'] = Carbon::now();
                $logsid = BwdSubEvent::create($subdata);
                $ids[] =$v['bwd_designerid'];
                $names[] =$subname;
            }
                if($data['type'] == 1){//串行
                    $firstname = AuthAdmin::where('id',$data['task'][0]['bwd_designerid'])->value('name');
                    $arr['bwd_designerid'] = $data['task'][0]['bwd_designerid'];//设计师
                    $arr['bwd_designer'] = $firstname;
                    $arr['bwd_nextid'] = $data['task'][0]['bwd_designerid'];//下一步操作人
                    $arr['bwd_nextname'] = $firstname;
                }else{//并行
                    $arr['bwd_designerid'] = implode(',',$ids);//设计师
                    $arr['bwd_designer'] = implode(',',$names);;
                    $arr['bwd_nextid'] = implode(',',$ids);//下一步操作人
                    $arr['bwd_nextname'] = implode(',',$names);
                }
                //var_dump(implode(',',$ids));die;
        }
        //接受任务    评价任务  bwd_pj
        if(in_array($data['bwd_flag'],array('bwd_js','bwd_pj'))){
            $arr['bwd_nextid'] = $user->id;
            $arr['bwd_nextname'] = $user->name;
        }
        //提交任务   拒绝任务 直接回到发布人   bwd_jj
        if(in_array($data['bwd_flag'],array('bwd_tj'))){
            //如果是串行,查找下一步的执行人.
            if($bwdinfo['type'] == 1){
                $bwdsubinfo = BwdSubEvent::where("bwdid",$data['id'])->where('bwd_status','40')->orderBy('sort')->first();
                if(isset($bwdsubinfo)){
                    $arr['bwd_nextid'] = $bwdsubinfo->bwd_designerid;
                    $arr['bwd_nextname'] = $bwdsubinfo->bwd_designer;
                    BwdSubEvent::where('id',$bwdsubinfo->id)->update(['state'=>1]);
                    BwdSubEvent::where('id',$data['sbwdid'])->update(['state'=>2]);
                }else{//任务全部完结
                    $arr['bwd_zt'] = true;
                    $arr['bwd_nextid'] = $user->id;
                    $arr['bwd_nextname'] = $user->name;
                }
            }else{

            }
        }
        //拒绝任务
        if(in_array($data['bwd_flag'],array('bwd_jj'))) {//
            $arr['bwd_nextid'] = $bwdinfo->bwd_issuerid;//发布人
            $arr['bwd_nextname'] = $bwdinfo->bwd_issuer;
        }
        return $arr;
    }

    //管理员审核
    public function Bwd_Event_Sh(array $data)
    {
        //审核单独设置,
        $user = Auth::user();
        $arr['present_id'] = $user->id;
        $arr['present_name'] = $user->name;
            //判断单行还是串行.
             $i = 0;
            foreach($data['task'] as $k=>$v){
                $i++;
                if(($data['type'] == 1) && ($i > 1)){
                    $subdata['state'] = 2;
                }
                if(is_string($v)){
                    $v = json_decode($v,true);
                    $data['task'][$k] = $v;
                }
                $subname = AuthAdmin::where('id',$v['bwd_designerid'])->value('name');
                $subdata['bwdid'] = $data['id'];
                $subdata['bwd_designer'] = $subname;
                $subdata['bwd_designerid'] = $v['bwd_designerid'];
                $subdata['bwd_status'] = '40';
                $subdata['taskname'] = $v['taskname'];
                $subdata['content'] = $v['content'];
                $subdata['type'] = $data['type'];
                $subdata['sort'] = $k;
                $subdata['created_at'] = Carbon::now();
                $logsid = BwdSubEvent::create($subdata);
                $ids[] =$v['bwd_designerid'];
                $names[] =$subname;
            }
            if($data['type'] == 1){//串行
                $firstname = AuthAdmin::where('id',$data['task'][0]['bwd_designerid'])->value('name');
                $arr['bwd_designerid'] = $data['task'][0]['bwd_designerid'];//设计师
                $arr['bwd_designer'] = $firstname;
                $arr['bwd_nextid'] = $data['task'][0]['bwd_designerid'];//下一步操作人
                $arr['bwd_nextname'] = $firstname;
            }else{//并行
                $arr['bwd_designerid'] = implode(',',$ids);//设计师
                $arr['bwd_designer'] = implode(',',$names);;
                $arr['bwd_nextid'] = implode(',',$ids);//下一步操作人
                $arr['bwd_nextname'] = implode(',',$names);
            }
            return $arr;
    }

    //任务转交给别的人
    public function event_Careof(array $data)
    {

    }

    //事件详情
    public function bwd_event($id)
    {


    }

    /*
     * 任务流转
     */
    public function taskAll(array $data)
    {
        //下一步的状态
        $bwdflag = $this->bwdFlag($data['bwd_flag']);
        //当前操作人和下一步操作人
        if($data['bwd_flag'] == 'bwd_sh'){
            $at_present = $this->Bwd_Event_Sh($data);
        }else{
            $at_present = $this->at_present($data);
        }

        //任务主表
        $bwdinfo = BwdEvent::find($data['id']);
       // var_dump($data);die;
        //用户操作,生成一条记录
        $this->logs_insert($data,$bwdinfo,$at_present);
        //子表记录更新
        if(isset($data['sbwdid']))  $this->sub_events($bwdflag['next'],$data['sbwdid'],$at_present);

        //主表记录更新
        $this->update_Bwd_Event($bwdflag['next'],$data,$at_present);

        return $this->apiSuccess('操作成功！');
    }

    public function update_Bwd_Event($bwdflag,$data,$at_present)
    {
        //主表更新 当前操作人是谁, 当前设计师
        $events['bwd_designerid'] = $at_present['bwd_nextid'];//当前操作人
        $events['bwd_designer'] = $at_present['bwd_nextname'];
        if(isset($data['eapectdate'])){
            $events['eapectdate'] = Carbon::parse($data['eapectdate'])->toDateTimeString();
        }
        if(isset($data['remark'])) {
            $events['remark'] = $data['remark'];
        }
        if(isset($at_present['bwd_zt']) && $at_present['bwd_zt'] == true){
            $bwdflag = '150';//主任务完结
        }
        $events['bwd_status'] =$bwdflag;
        $events['atpresent_id'] = $at_present['bwd_nextid'];//当前操作人
        $events['updated_at'] = Carbon::now();
        BwdEvent::where('id',$data['id'])->update($events);

    }

    //日志记录
    public function logs_insert($data,$bwdinfo,$at_present)
    {
        //var_dump($name);die;
        $logs['bwdid'] = $data['id'];
        if(isset($data['sbwdid'])){
            $logs['sbwdid'] = $data['sbwdid'];
        }
        $logs['bwd_flag'] = $data['bwd_flag'];
        $logs['bwd_status'] = $bwdinfo->bwd_status;//执行操作之前的状态
        $logs['bwd_operatorid'] = $at_present['present_id'];//当前操作人
        $logs['bwd_operator'] = $at_present['present_name'];
        $logs['bwd_contnet'] = 'hehe';
        $logs['bwd_nextoperator'] = $at_present['bwd_nextname'];//下一步操作人
        $logs['bwd_nextoperatorid'] = $at_present['bwd_nextid'];
        $logs['created_at'] = Carbon::now();
        $logsid = BwdLog::create($logs);
        //记录变更表 BwdChangeRecord
        $this->bwd_Change_insert($data);
    }
    //任务变更记录
    public function bwd_Change_insert($data)
    {
        $bwd_info = BwdEvent::find($data['id']);
        foreach($data as $k=>$v){
            if (!in_array($k,array('id','task','type','bwd_flag')) && $bwd_info->$k !=$v){
                $arr['bwdid'] = $data['id'];
                $arr['bwd_field'] = $k;
                $arr['bwd_fieldname'] =$this->getAnnotation($k);
                $arr['bwd_auld'] = $bwd_info->$k??'空';
                $arr['bwd_fresh'] = $v;
                if($k == 'bwd_designerid'){
                    $nextname = AuthAdmin::where('id',$v)->value('name');
                    $arr['bwd_fresh'] = $nextname;
                }
                $arr['created_at'] = Carbon::now();
                $arr['bwd_operator'] = Auth::user()->name;
                $arr['bwd_operatorid'] =Auth::user()->id;
                BwdChangeRecord::create($arr);
            }
        }
       // var_dump($data);die;
    }

    public function getAnnotation($key)
    {
        $zs = DB::select("SELECT
	COLUMN_COMMENT
FROM
	information_schema.COLUMNS
WHERE
	TABLE_SCHEMA = 'admin_mkl'
	AND TABLE_NAME = 'lv_bwd_events'
	AND COLUMN_NAME =?",[$key]);
        if($zs){
            return $zs[0]->COLUMN_COMMENT;
        }
        return false;

    }

    //子表个人任务更新
    public function sub_events($status,$sbwdid,$at_present)
    {
        $sub_data['bwd_designer'] = $at_present['bwd_nextname'];//当前操作人
        $sub_data['bwd_designerid'] = $at_present['bwd_nextid'];
        $sub_data['bwd_status'] =$status;
        $sub_data['updated_at'] = Carbon::now();
        //var_dump($sub_data);die;
        BwdSubEvent::where('id',$sbwdid)->update($sub_data);
    }

    /*
    *详情页面
    */
    public function show(int $id){
        $userinfo = Auth::user();
        //任务主表
        $bwdinfo = BwdEvent::find($id);
        //var_dump($this->show_status(10));die;
        //变更记录
        $change_info = BwdChangeRecord::where('bwdid',$id)
            ->select("bwd_fieldname","bwd_auld","bwd_fresh","created_at","bwd_operator")
            ->orderBy("created_at")
            ->get()->Toarray();

        //审批进度  根据子任务来排列
        //分为三步. 一.申请. 二.审核. 三.子任务, 四.完成
        $data['bwd_sq'] = BwdLog::where("bwd_flag","bwd_sq")->where("bwdid",$id)->first();
        $data['bwd_sh'] = BwdLog::where("bwd_flag","bwd_sh")->where("bwdid",$id)->first();
        $list = BwdSubEvent::where("bwdid",$id)->get()->Toarray();
        //var_dump($change_info);die;
        $data['data'] = BwdSubEvent::where("bwdid",$id)->get()->Toarray();
        foreach($data['data']  as $k=>$v){

            $logs_arr = BwdLog::where("bwdid",$id)->where("sbwdid",$v['id'])->get()->Toarray();
            if(isset($logs_arr)){
                $data['data'][$k]['list'] = $logs_arr;
            }
        }
        //根据子任务的状态获取用户此时应该显示的按钮
        $subuser_status = BwdSubEvent::where("bwdid",$id)->where("bwd_designerid",$userinfo->id)->value('bwd_status');
        //var_dump($subuser_status);die;
        if(!isset($subuser_status)){
            $subuser_status = '10';

        }
        $butinfo = $this->show_status($subuser_status);

        return $this->apiSuccess('',[
            'userinfo'=>$userinfo,
            'bwdinfo'=>$bwdinfo,
            'changeinfo'=>$change_info,
            'butinfo'=>$butinfo,
            'data'=>$data,
            'list'=>$list
        ]);
    }

    public function show_status($state)
    {
        $userid = Auth::user()->id;
        if($state == '10' && $userid != '1'){
            return [];
        }
        $opac = $this->bwdFlag('',2);
        foreach($opac as $k=>$v){
            if(in_array($state,$v['triggerStatus'])){
               $arr[] = $k;
            }
        }
       return $arr;
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


    }
}
