<?php
/**
 * @Name 头条相关接口
 * @Description
 * @Auther fengbin
 * @Date 20220809
 */

namespace Modules\Admin\Services\onces;

use Illuminate\Support\Facades\Config;
use Modules\Admin\Models\BusinessTeam;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\OncesAdvertiser;
use Modules\Admin\Models\AdPlanStatistic;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;
use Modules\Common\Services\BaseService;
use Modules\Common\CommonTrait\AdApiRequestTrait;
use Modules\Common\Services\OncesService as OncesApiService;

class OncesService extends BaseApiService
{
    use AdApiRequestTrait;

    /**
     * @name 头条相关接口
     * @return JSON
     **/

    public function uploadVideo($params)
    {
        if ($params['advertiser_id']) {
            $advertiserModel = OncesAdvertiser::query();
            $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
//            var_dump($params['video_file']);exit;
        }
        $data = [
            'advertiser_id' => $params['advertiser_id'],
            'video_file' => $params['video_file'],
            'video_signature' => md5_file($params['video_file']),
            'access_token' => $advertiser->access_token,
        ];

        $res = OncesApiService::uploadVideo($data);

//        var_dump($advertiser->advertiser_id, $advertiser->access_token);exit;
    }

    //获取视频封面  第一次不会返回封面  要等待6s左右  第二次请求会返回智能封面
    public function getVideoCover($params)
    {
        if ($params['advertiser_id']) {
            $advertiserModel = OncesAdvertiser::query();
            $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
        }
        $data = ['apiUrl' => config('onces')['oncesapi']['getVideoCover']];
        $data['apiData'] = [
            'advertiser_id' => $params['advertiser_id'],
            'video_id' => $params['video_id']
        ];
        $data['headers'] = ['Access-Token: ' . $advertiser->access_token];
        $data['logname'] = __function__;
        $data['method'] = 'GET';
        $res = $this->curlPackage($data);
        if (!empty($res['status']) && $res['status'] == StatusData::Ok) {
            //返回正常 处理数据
            var_dump($res);
            exit;
        }
        return $res;
    }

    public function proceduralCreative($params)
    {
        $advertiserModel = OncesAdvertiser::query();
        $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
        $adData = $creative = [];
        if (is_array($params['title_list'])) {
            foreach ($params['title_list'] as $key => $val) {
                $creative['title_materials'][$key]['title'] = $val['title'];
                if (isset($val['word_ids'])) {
                    $wordList = [];
                    foreach ($val['word_ids'] as $wordVal) {
                        $wordList[] = ['word_id' => $wordVal];
                    }
                    $creative['title_materials'][$key]['word_list'] = $wordList;
                }
                $creative['title_materials'][$key]['title'] = $val['title'];
            }
        } else {
            return $this->apiError('创意标题参数格式错误');
        }

        if (is_array($params['video_list'])) {
            foreach ($params['video_list'] as $vKey => $vVal) {
                $creative['video_materials'][$vKey]['video_info']['video_id'] = $vVal['video_id'] ?? '';
                $creative['video_materials'][$vKey]['image_info']['image_id'] = $vVal['image_id'] ?? '';
                $creative['video_materials'][$vKey]['image_mode'] = $vVal['image_mode'] ?? 'CREATIVE_IMAGE_MODE_VIDEO_VERTICAL';
            }
        } else {
            return $this->apiError('视频参数格式错误');
        }

        if (is_array($params['component_ids'])) {
            foreach ($params['component_ids'] as $cVal) {
                $creative['component_materials'][] = ['component_id' => $cVal];
            }
        }

        !empty($params['third_industry_id']) && $adData['third_industry_id'] = $params['third_industry_id'];
        !empty($params['ad_keywords']) && $adData['ad_keywords'] = $params['ad_keywords'];
        !empty($params['external_url']) && $adData['external_url'] = $params['external_url'];
        !empty($params['source']) && $adData['source'] = $params['source'];
        !empty($params['is_comment_disable']) && $adData['is_comment_disable'] = $params['is_comment_disable'];

        $apiData = [
            'advertiser_id' => $params['advertiser_id'],
            'ad_id' => $params['ad_id'],
            'creative' => $creative,
            'ad_data' => $adData
        ];
        $headers = [
            'Access-Token: ' . $advertiser->access_token
        ];
        $data = [
            'apiUrl' => config('onces')['oncesapi']['proceduralCreative'],
            'apiData' => $apiData,
            'headers' => $headers,
            'logname' => __function__
        ];
        $res = $this->curlPackage($data);
        if (!empty($res['status']) && $res['status'] == StatusData::Ok) {
            //返回正常 处理数据
            var_dump($res);
            exit;
        }
        return $res;
    }

    public function customCreative($params)
    {
        $advertiserModel = OncesAdvertiser::query();
        $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
        $titleList = $params['creative_list'];
        $creativeList = [];
        foreach ($params['creative_list'] as $key => $val) {
            $creativeList[$key]['title_material']['title'] = $val['title'];
            if (!empty($val['word_ids'])) {
                foreach ($val['word_ids'] as $wordIdVal) {
                    $creativeList[$key]['title_material']['word_list'][] = ['word_id' => $wordIdVal];
                }
            }
            $creativeList[$key]['video_material']['image_info']['image_id'] = $val['video_list']['image_id'];
            $creativeList[$key]['video_material']['video_info']['video_id'] = $val['video_list']['video_id'];
//            if()

            $creativeList[$key]['title_material']['title'] = $val['title'];
        }


    }

    public function getComponent($params)
    {
        $aa = config('oncesapi');
        var_dump($aa);
        exit;
        $advertiserModel = OncesAdvertiser::query();
        $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
        $params['access_token'] = $advertiser->access_token;
        $res = OncesApiService::getComponent($params);
        echo "<pre>";
        print_r($res);
        exit;

    }

    public function pullOncesAdverts($params)
    {
        $advertiserModel = OncesAdvertiser::query();
        $advertiser = BaseService::getAdvertiserInfo($advertiserModel, $params['advertiser_id']);
        $params['access_token'] = $advertiser->access_token;
        !empty($params['ad_ids']) && $filters['ids'] = $params['ad_ids'];
        !empty($params['campaign_id']) && $filters['campaign_id'] = $params['campaign_id'];
        !empty($params['ad_name']) && $filters['ad_name'] = $params['ad_name'];
        $res = OncesApiService::getAdvertList($params);
        $return = json_decode($res, true);
        if (!empty($return) && isset($return['code']) && $return['code'] == 0) {
            $list = $return['data']['list'][0];
//            echo "<pre>";print_r($list);exit;
            $this->insertAutoPlan($list, ['access_token' => $params['access_token'], 'advertiser_id' => $params['advertiser_id']]);

            $audience = $this->audience($list['audience'], ['access_token' => $params['access_token'], 'advertiser_id' => $params['advertiser_id']]);
        } else {
            $message = $return['message'] ?? '系统错误';
            $this->log('get-onces-advert', $message, ['url' => 'pullOncesAdverts', 'getParams' => $params, 'return' => $return]);
        }


        echo "<pre>";
        print_r($res);
        exit;
    }

    //临时性的添加到自动化任务的头条计划表
    public function insertAutoPlan($data, $advertiserData)
    {
//        echo "<pre>";print_r($a);exit;
        $inventoryType = $data['inventory_type'];
        $inventoryStr = '';
        $inventoryArr = [
            'INVENTORY_TOMATO_NOVEL' => '番茄小说',
            'INVENTORY_FEED' => '今日头条',
            'INVENTORY_VIDEO_FEED' => '西瓜视频',
            'INVENTORY_AWEME_FEED' => '抖音'
        ];
        $location = [
            'CURRENT' => '正在该地区的用户',
            'HOME' => '居住在该地区的用户',
            'TRAVEL' => '到该地区旅行的用户',
            'ALL' => '该地区内的所有用户',
        ];
        foreach ($inventoryType as $val) {
            $inventoryStr .= $inventoryArr[$val] . ',';
        }
        $sex = [
            'NONE' => '不限',
            'GENDER_UNLIMITED' => '不限',
            'GENDER_MALE' => '男',
            'GENDER_FAMALE' => '女',
        ];

        $ages = [
            'AGE_BETWEEN_18_23' => '18-23',
            'AGE_BETWEEN_24_30' => '24-30',
            'AGE_BETWEEN_31_40' => '31-40',
            'AGE_BETWEEN_41_49' => '41-49',
            'AGE_ABOVE_50' => '50+'
        ];
        $age = '';
        if (empty($data['audience']['age'])) {
            $age = '不限';
        } else {
            foreach ($data['audience']['age'] as $ageVal) {
                $age .= $ages[$ageVal] . ',';
            }
        }
        $interestActionMode = $data['audience']['interest_action_mode'];
        //不限,自定义,
        if ($interestActionMode == 'UNLIMITED') {
            $inacMode = '不限';
        } elseif ($interestActionMode == 'RECOMMEND') {
            $inacMode = '系统推荐';
        } elseif ($interestActionMode == 'CUSTOM') {
            $inacMode = '自定义,';
            $acScene = [
                'E-COMMERCE' => '电商互动行为',
                'NEWS' => '资讯互动行为',
                'APP' => 'App推广互动行为',
                'SEARCH' => '搜索互动行为'
            ];
            if (!empty($data['audience']['action']['action_scene'])) {
                foreach ($data['audience']['action']['action_scene'] as $scVal) {
                    $inacMode .= $acScene[$scVal] . ',';
                }
            } else {
                $inacMode .= '所有行为场景,';
            }
            $inacMode .= $data['audience']['action']['action_days'] . '天';

        }
        if (!empty($data['audience']['auto_extend_enabled']) && $data['audience']['auto_extend_enabled'] == 1) {
            $autoExtend = [
                'REGION' => '地域',
                'GENDER' => '性别',
                'AGE' => '年龄',
                'AD_TAG' => '兴趣分类',
                'INTEREST_TAG' => '兴趣关键词',
                'CUSTOM_AUDIENCE' => '自定义人群包',
                'INTEREST_ACTION' => '行为兴趣'
            ];
            $autoStr = '启用,';
            foreach ($data['audience']['auto_extend_targets'] as $tarVal) {
                $autoStr .= $autoExtend[$tarVal] . ',';
            }
            $autoStr = trim($autoStr, ',');

        }

        //SMART_BID_CUSTOM, 放量投放SMART_BID_CONSERVATIVE
        if (!empty($data['smart_bid_type'])) {
            $smartType = [
                'SMART_BID_CUSTOM' => '常规投放',
                'SMART_BID_CONSERVATIVE' => '放量投放'
            ];
            $launch = $smartType[$data['smart_bid_type']];
        }
        $exceptUserArr = [
            'NO_EXCLUDE' => '不过滤',
            'AD' => '广告计划',
            'CAMPAIGN' => '广告组',
            'ADVERTISER' => '广告账户',
            'APP' => 'APP',
            'CUSTOMER' => '公司账户',
            'ORGANIZATION' => '组织账户'
        ];

        $inventoryStr = trim($inventoryStr, ',');
        //行为兴趣转换文字
        $inacData = $this->audience($data['audience'], $advertiserData);
        $insertData = [
            'ad_id' => $data['ad_id'],
            'plan_name' => $data['name'],
            'campaign_id' => $data['campaign_id'],
            'advertiser_id' => $data['advertiser_id'],
            'optimize_id' => !empty($data['asset_ids']) ? $data['asset_ids'][0] : '',
            'optimize_target' => 'app内下单',  //转化类型枚举  AD_CONVERT_TYPE_APP_ORDER
            'link_content' => !empty($data['open_url']) ? $data['open_url'] : '',
            'delivery_platform' => $inventoryStr,
//            'delivery_province' => $location[$data['audience']['location_type']],
            'release_user_area' => $location[$data['audience']['location_type']],
            'sex' => $sex[$data['audience']['gender']],
            'age' => $age,
            'behavioral_interest' => $inacMode,
            'iphone_platform' => '不限',
            'device_type' => '不限',
            'network' => '不限',
            'except_user' => $exceptUserArr[$data['hide_if_converted']] ?? '',
            'ai_scale' => $autoStr,

            'launch_scenario' => $launch,
            'launch_timestmp' => '6-23',
            'day_budget' => $data['budget'],
            'conversion_bid' => $data['cpa_bid'],
            'others_link' => $data['track_url'][0] ?? '',
            'behavior_category' => $inacData['acc'] ?? '',
            'behavior_keysword' => $inacData['acw'] ?? '',
            'interest_category' => $inacData['inc'] ?? '',
            'interest_keysword' => $inacData['inw'] ?? '',
        ];
        $modelAuto = AdPlanStatistic::query();
        $return = $this->commonCreateLastId($modelAuto, $insertData, '任务订阅成功！');
        var_dump($return);
    }

    //定向包数据处理
    public function audience($params, $advertiserData)
    {
        $params['access_token'] = $advertiserData['access_token'];
        if ($params['interest_action_mode'] == 'CUSTOM') {
            //自定义行为兴趣
            $actionCategoriesIds = $params['action']['action_categories'];
            $actionDays = $params['action']['action_days'];
            $actionScene = $params['action']['action_scene'];
            //关键词的id集
            $actionWords = $params['action']['action_words'];
            $actionCate = [
                'advertiser_id' => $advertiserData['advertiser_id'],
                'access_token' => $advertiserData['access_token'],
                'ids' => $actionCategoriesIds,
                'tag_type' => 'CATEGORY',
                'targeting_type' => 'ACTION',
                'action_scene' => $actionScene,
                'action_days' => $actionDays
            ];
            $actionWord = [
                'advertiser_id' => $advertiserData['advertiser_id'],
                'access_token' => $advertiserData['access_token'],
                'ids' => $actionWords,
                'tag_type' => 'KEYWORD',
                'targeting_type' => 'ACTION',
                'action_scene' => $actionScene,
                'action_days' => $actionDays
            ];
            $interestCate = [
                'advertiser_id' => $advertiserData['advertiser_id'],
                'access_token' => $advertiserData['access_token'],
                'ids' => $params['interest_categories'],
                'tag_type' => 'CATEGORY',
                'targeting_type' => 'INTEREST'
            ];
            $interestWord = [
                'advertiser_id' => $advertiserData['advertiser_id'],
                'access_token' => $advertiserData['access_token'],
                'ids' => $params['interest_words'],
                'tag_type' => 'KEYWORD',
                'targeting_type' => 'INTEREST'
            ];
            $accStr = $incStr = $acwStr = $inwStr = '';
            if (!empty($actionCategoriesIds)) {
                $acCateRes = OncesApiService::getIdToWords($actionCate);
                $acCateData = json_decode($acCateRes, true);
                if (!empty($acCateData) && isset($acCateData['code']) && $acCateData['code'] == 0) {
//                    $acCateList = $acCateData['data']['categories'];
                    $accName = array_column($acCateData['data']['categories'], 'name');
                    $accStr = implode(',', $accName);
                }
            }
            if (!empty($actionWords)) {
                $acWordRes = OncesApiService::getIdToWords($actionWord);
                $acWordData = json_decode($acWordRes, true);
                if (!empty($acWordData) && isset($acWordData['code']) && $acWordData['code'] == 0) {
                    $acwName = array_column($acWordData['data']['keywords'], 'name');
                    $acwStr = implode(',', $acwName);
                }
            }
            if (!empty($params['interest_categories'])) {
                $inCateRes = OncesApiService::getIdToWords($interestCate);
                $inCateData = json_decode($inCateRes, true);
                if (!empty($inCateData) && isset($inCateData['code']) && $inCateData['code'] == 0) {
                    $incName = array_column($inCateData['data']['categories'], 'name');
                    $incStr = implode(',', $incName);
                }
            }
            if (!empty($params['interest_words'])) {
                $inWordRes = OncesApiService::getIdToWords($interestWord);
                $inWordData = json_decode($inWordRes, true);
                if (!empty($inWordData) && isset($inWordData['code']) && $inWordData['code'] == 0) {
                    $inwName = array_column($inWordData['data']['keywords'], 'name');
                    $inwStr = implode(',', $inwName);
                }
            }
            return [
                'acc' => $accStr,
                'acw' => $acwStr,
                'inc' => $incStr,
                'inw' => $inwStr,
            ];
            echo "<pre>";
            print_r($acCateList);
            print_r($acWordList);
            print_r($inCateList);
            print_r($inWordList);
            exit;
        }
//        $res = OncesApiService::getAdvertList($params);
//        echo "<pre>";print_r($res);exit;
    }

}
