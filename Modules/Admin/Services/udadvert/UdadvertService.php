<?php
/**
 * @Name ud广告自动创建
 * @Description
 * @Auther fengbin
 * @Date 20220726
 */
namespace Modules\Admin\Services\Udadvert;

use Modules\Admin\Models\UdAutomation;
use Modules\Admin\Models\AdPlanStatistic;
use Modules\Admin\Models\AutoadvertLog;
use Modules\Admin\Models\UdCreativeCategory;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\Exceptions\MessageData;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
class UdadvertService extends BaseApiService
{
    /**
     * @name ud广告自动创建
     * @return JSON
     **/
    public function index(array $data)
    {

        $model = UdAutomation::query();
        $model = $this->queryCondition($model,$data,'plan_name');
        $list = $model->select()
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        foreach($list['data'] as $key=>$val)
        {
            $list['data'][$key]['xingwei'] = $val['xingwei_category'].$val['xingwei_keyword'];
            $list['data'][$key]['xingqu'] = $val['xingqu_category'].$val['xingqu_keyword'];
        }

        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    public function add(array $params)
    {
        return $this->commonCreate(UdAutomation::query(), $params);
    }

    public function detail(int $id)
    {
        if(empty($id)){
            return $this->apiError(MessageData::MISS_PARAMS_ERROR);
        }
        return $this->apiSuccess('',UdAutomation::select()->find($id)->toArray());
    }

    public function updateAdvert(array $data)
    {
        //python执行的任务key
        $key = 'auto:ud:advert:task';
//        $a = redis::rpop($key);var_dump($a);exit;
        /*if (strpos($data['dir_video_link'], "\\\\file.maclove.com\电商运营") === false){
            $this->apiError('视频路径不合格，无法读取');
        }*/
        if (!empty($data['pid'])){
            $creativeCate = UdCreativeCategory::getCategoryName((int)$data['pid']);
        }
        if ($data['creative_type'] == '自定义创意'){
            $data['creative_title_nums'] = 1;
        }
        $redisArr = [
            'advertiser_id' => $data['udadvertiser_id'] ?? '',
            'campaign_id' => $data['udcampaign_id'] ?? '',
            'ud_plan_id' => $data['id'] ?? '',
            'dir_video_link' => $data['dir_video_link'] ?? '',
            'creative_source' => $data['creative_source'] ?? '',
            'creative_title_nums' => $data['creative_title_nums'] ?? 1,
            'creative_label' => $data['creative_label'] ?? '',
            'creative_title' => $data['creative_title'] ?? '',
            'creative_category' => empty($creativeCate) ? '美妆/特殊化妆品/祛斑美白' : $creativeCate,
            'component_id' => $data['component_id'] ?? '',
            'creative_component_id' => $data['creative_component_id'] ?? '',
            'video_type' => $data['video_type'] ?? '',
            'creative_type' => $data['creative_type'] ?? '',
            'is_derive_creative' => !empty($data['is_derive_creative']) ?  $data['is_derive_creative'] : '关闭',
            'is_review' => !empty($data['is_review']) ?  $data['is_review'] : '关闭',
            'autoadvert_logs_id' => '',
        ];
        $modelAuto = AutoadvertLog::query();
        $userId = Auth::id();

        $addData = [
          'ud_automations_id' => $data['id'] ?? '',
          'user_id' => $userId ?? '',
          'ip' => request()->ip(),
          'content' => json_encode($redisArr)
        ];
        $return = $this->commonCreateLastId($modelAuto, $addData, '任务订阅成功！');
        $redisArr['autoadvert_logs_id'] = $return->getData(true)['data']['lastId'] ?? '';
        redis::lpush($key, json_encode($redisArr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
//        $a = redis::rpop($key);
        return $return;

    }

    //获取到创意分类
    public function getCategory()
    {

//        $users = DB::table('ud_creative_categorys')->leftJoin('ud_creative_categorys','users.id','=','posts.user_id')->get();
        $model = UdCreativeCategory::query();
        $list = $model->orderBy('id','asc')
            ->get()
            ->toArray();
        return $this->apiSuccess('',$this->tree($list));
    }

    public function update(int $id, array $data)
    {
        return $this->commonUpdate(UdAutomation::query(), $id, $data);
    }

    //头条拉去的计划
    public function oncesIndex(array $data)
    {
        $model = AdPlanStatistic::query();
        $model = $this->queryCondition($model,$data,'plan_name');
        $list = $model->select()
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    public function oncesDetail(int $id)
    {
        if(empty($id)){
            return $this->apiError(MessageData::MISS_PARAMS_ERROR);
        }
        return $this->apiSuccess('',AdPlanStatistic::select()->find($id)->toArray());
    }

    public function updateOncesAdvert(array $data)
    {
        //python执行的任务key
        $key = 'auto:onces:advert:task';

        /*if (strpos($data['dir_video_link'], "\\\\file.maclove.com\电商运营") === false){
            $this->apiError('视频路径不合格，无法读取');
        }*/
        if (!empty($data['pid'])){
            $creativeCate = UdCreativeCategory::getCategoryName((int)$data['pid']);
        }
        if ($data['creative_type'] == '自定义创意'){
            $data['creative_title_nums'] = 1;
        }
        $redisArr = [
            'aadvid' => $data['udadvertiser_id'] ?? '', //广告主
            'campaign_id' => $data['udcampaign_id'] ?? '',
            'ud_plan_id' => $data['id'] ?? '',
            'dir_video_link' => $data['dir_video_link'] ?? '',
            'creative_source' => $data['creative_source'] ?? '',
            'creative_title_nums' => $data['creative_title_nums'] ?? 1,
            'creative_label' => $data['creative_label'] ?? '',
            'creative_title' => $data['creative_title'] ?? '',
            'creative_category' => empty($creativeCate) ? '美妆/特殊化妆品/祛斑美白' : $creativeCate,
            'component_id' => $data['component_id'] ?? '',
            'creative_component_id' => $data['creative_component_id'] ?? '',
            'creative_type' => $data['creative_type'] ?? '',
            'land_link_id' => $data['land_link_id'] ?? '',
            'autoadvert_logs_id' => '',
        ];
        $modelAuto = AutoadvertLog::query();
        $userId = Auth::id();

        $addData = [
            'ud_automations_id' => $data['id'] ?? '',
            'user_id' => $userId ?? '',
            'type' => 1,
            'ip' => request()->ip(),
            'content' => json_encode($redisArr)
        ];
        $return = $this->commonCreateLastId($modelAuto, $addData, '任务订阅成功！');
        $redisArr['autoadvert_logs_id'] = $return->getData(true)['data']['lastId'] ?? '';
        redis::lpush($key, json_encode($redisArr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
//        echo json_encode($redisArr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;
        return $return;

    }
}
