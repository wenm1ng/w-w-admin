<?php
/**
 * @Name  请求头条api
 */

namespace Modules\Common\Services;

use Illuminate\Support\Facades\DB;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\CodeData;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;

class OncesService
{
    protected static $oncesUrl = [
        //上传视频
        'uploadVideo' => 'https://ad.oceanengine.com/open_api/2/file/video/ad/',
        'getVideo' => 'https://ad.oceanengine.com/open_api/2/file/video/get/',
        //获取视频的智能封面
        'getVideoCover' => 'https://ad.oceanengine.com/open_api/2/tools/video_cover/suggest/',
        //自定义创意
        'customCreative' => 'https://ad.oceanengine.com/open_api/2/creative/custom_creative/create/',
        //程序化创意
        'proceduralCreative' => 'https://ad.oceanengine.com/open_api/2/creative/procedural_creative/create/',
        //动态创意词包
        'getCreativeWord' => 'https://ad.oceanengine.com/open_api/2/tools/creative_word/select/',
        //获取广告计划
        'getAdvertList' => 'https://ad.oceanengine.com/open_api/2/ad/get/',
        //行为关键词
        'getActionKeyword' => 'https://ad.oceanengine.com/open_api/2/tools/interest_action/action/keyword/',
        //行为类目
        'getActionCategory' => 'https://ad.oceanengine.com/open_api/2/tools/interest_action/action/category/',
        //兴趣关键词查询
        'getInterestKeyword' => 'https://ad.oceanengine.com/open_api/2/tools/interest_action/interest/keyword/',
        //兴趣类目查询
        'getInterestCategory' => 'https://ad.oceanengine.com/open_api/2/tools/interest_action/interest/category/',
        //兴趣行为类目关键词id转词
        'getIdToWord' => 'https://ad.oceanengine.com/open_api/2/tools/interest_action/id2word/',
        //查询组件列表
        'getComponent' => 'https://ad.oceanengine.com/open_api/2/assets/creative_component/get/',
    ];



    public function __construct()
    {

    }
    public function curlPackage($params)
    {
        //-----

        $return = json_decode($params, true);


    }
    public static function uploadVideo($params)
    {
        $videoUrl = self::$oncesUrl['uploadVideo'];
        $apiData = [
            'video_signature' => $params['video_signature'],
            'video_file' => $params['video_file'],
            'advertiser_id' => $params['advertiser_id']
        ];
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: multipart/form-data'
        ];
        $res = postFile($videoUrl, $apiData, $headers, 'POST', ['file_filed' => 'video_file']);
        $return = json_decode($res, true);
    }

    public static function getVideoCover($params)
    {
        $url = self::$oncesUrl['getVideoCover'];
        $apiData = [
            'advertiser_id' => $params['advertiser_id'],
            'video_id' => $params['video_id']
        ];
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: application/json'
        ];
        $res = httpRequest($url, $apiData, $headers, 'GET');
    }

    public static function createCreative($params)
    {
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: application/json'
        ];
        unset($params['access_token']);
        $apiData = $params;
        $res = httpRequest(self::$oncesUrl['proceduralCreative'], $apiData, $headers );
        return $res;
    }

    public static function getComponent($params)
    {
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: application/json'
        ];
        unset($params['access_token']);
        $apiData = $params;
        $res = httpRequest(self::$oncesUrl['getComponent'], $apiData, $headers, 'GET');
        return $res;

    }

    public static function getAdvertList($params)
    {
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: application/json'
        ];
        $fields = ['ad_id', 'name', 'campaign_id', 'advertiser_id', 'modify_time', 'ad_modify_time', 'ad_create_time', 'status', 'opt_status', 'inventory_catalog', 'inventory_type', 'track_url', 'convert_id', 'external_action', 'open_url', 'audience', 'asset_ids', 'smart_bid_type', 'adjust_cpa', 'flow_control_mode', 'budget_mode', 'budget', 'schedule_type', 'start_time', 'end_time', 'schedule_time', 'pricing', 'bid', 'cpa_bid', 'deep_bid_type', 'deep_cpabid', 'feed_delivery_search', 'search_bid_ratio', 'audience_extend', 'search_bid_ratio', 'hide_if_converted'];

        $apiData = [
            'advertiser_id' => $params['advertiser_id']
        ];
        $filters = [];
        !empty($params['ad_ids']) && $filters['ids'] = $params['ad_ids'];
        !empty($params['campaign_id']) && $filters['campaign_id'] = $params['campaign_id'];
        !empty($params['ad_name']) && $filters['ad_name'] = $params['ad_name'];
        $apiData['filtering'] = $filters;
        $apiData['fields'] = $fields;
        $res = httpRequest(self::$oncesUrl['getAdvertList'], $apiData, $headers, 'GET' );
        return $res;
    }

    public static function getIdToWords($params)
    {
        $headers = [
            'Access-Token: '.$params['access_token'],
            'Content-Type: application/json'
        ];
        //这些是必填
        $apiData = [
            'advertiser_id' => $params['advertiser_id'],
            'ids' => $params['ids'],
            'tag_type' => $params['tag_type'],
            'targeting_type' => $params['targeting_type'],
        ];
        //当 查询目标为行为时必填  行为场景 行为天数
        if ($params['targeting_type'] == 'ACTION'){
            $apiData['action_scene'] = $params['action_scene'];
            $apiData['action_days'] = $params['action_days'];
        }
        $res = httpRequest(self::$oncesUrl['getIdToWord'], $apiData, $headers, 'GET' );
        return $res;
    }

}
