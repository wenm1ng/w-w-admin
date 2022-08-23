<?php

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Admin\Services\onces\OncesService;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\Validator\Validator;
use Modules\Admin\Services\platform\AuthService;

class OncesController extends BaseApiController
{

    public function __construct()
    {
        $this->service = new OncesService();
        parent::__construct();
    }

    //上传头条视频
    public function uploadVideo(Request $request)
    {
        $params = $request->only([
            'advertiser_id', 'video_file'
        ]);
        Validator::check($params, [
            'video_file' => 'required',
            'advertiser_id' => 'required'
        ], [
            'video_file.required' => '视频地址不能为空',
            'advertiser_id.required' => '广告主不能为空'
        ]);
        return $this->service->uploadVideo($params);
    }

    public function getVideoCover(Request $request)
    {
        $params = $request->only([
            'advertiser_id', 'video_id'
        ]);
        Validator::check($params, [
            'video_id' => 'required',
            'advertiser_id' => 'required'
        ], [
            'video_file.required' => '视频地址不能为空',
            'advertiser_id.required' => '广告主不能为空'
        ]);
        return $this->service->getVideoCover($params);
    }
    //创建创意
    public function addCreative(Request $request)
    {
        $params = $request->only([
            'advertiser_id', 'ad_id', 'title_list', 'video_list', 'component_ids', 'third_industry_id', 'ad_keywords', 'external_url', 'source', 'is_comment_disable'
        ]);
        Validator::check($params, [
            'ad_id' => 'required',
            'title_list' => 'required',
            'video_list' => 'required',
            'source' => 'required',
            'advertiser_id' => 'required'
        ], [
            'advertiser_id.required' => '广告主不能为空',
            'ad_id.required' => '广告计划不能为空',
            'title_list.required' => '标题不能为空',
            'video_list.required' => '视频不能为空',
            'source.required' => '广告计划不能为空',

        ]);
        return $this->service->proceduralCreative($params);
    }

    public function addCustomCreative(Request $request)
    {
//        $params = $request->only([
//            'advertiser_id', 'ad_id', 'title_list', 'video_list', 'component_ids', 'third_industry_id', 'ad_keywords', 'external_url', 'source', 'is_comment_disable'
//        ]);
        $params = $request->all();
        return $this->service->customCreative($params);
        var_dump($params);exit;


    }

    public function pullOncesAdvert(Request $request)
    {
        $params = $request->only([
            'advertiser_id', 'ad_ids', 'campaign_id', 'ad_name', 'ad_create_time', 'ad_modify_time'
        ]);
        Validator::check($params, [
            'advertiser_id' => 'required'
        ], [
            'advertiser_id.required' => '广告主不能为空'
        ]);
        return $this->service->pullOncesAdverts($params);
    }

    public function getOncesComponent(Request $request)
    {
        $params = $request->only([
            'advertiser_id'
        ]);
        Validator::check($params, [
            'advertiser_id' => 'required'
        ], [
            'advertiser_id.required' => '广告主不能为空'
        ]);
        return $this->service->getComponent($params);
    }

    public function freshToken(Request $request)
    {
        $authModel = new AuthService();
        $params = $request->only([
            'account_id'
        ]);
        return $authModel->authFreshToken($params['account_id']);
    }
}
