<?php
/**
 * @Name UD广告
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\Udadvert\UdadvertService;
use Modules\Common\Validator\Validator;

class UdadvertController extends BaseApiController
{
    /**
     * @name 团队列表
     * fb  20220709
     **/
    public function index(CommonPageRequest $request)
    {
        return (new udadvertService())->index($request->only([
            'page',
            'limit',
            'plan_name'
        ]));
    }

    public function getDetail(Request $request)
    {
        return (new udadvertService())->detail($request->get('id'));
    }

    public function updateUdadvert(Request $request)
    {
//        $mac = $this->getWindows();
        $redisArr = $request->only(['dir_video_link','creative_source', 'pid', 'udcampaign_id',  'udadvertiser_id', 'id', 'creative_title_nums', 'creative_title', 'creative_label', 'creative_category', 'component_id', 'creative_component_id', 'video_type', 'creative_type', 'is_derive_creative', 'is_review']);
        Validator::check($redisArr, [
            'video_type' => 'required|string',
            'creative_type' => 'required|string',
            'dir_video_link' => 'required|string',
            'creative_title' => 'required|string',
            'creative_label' => 'required|string',
            'udcampaign_id' => 'required|integer',
            'udadvertiser_id' => 'required|integer'
        ], [
            'video_type.required' => '视频类型不能为空',
            'creative_type.required' => '创意类型不能为空',
            'dir_video_link.required' => '视频路径不能为空',
            'creative_title.required' => '创意标题不能为空',
            'creative_label.required' => '创意标签不能为空',
            'udcampaign_id.required' => '广告组ID不能为空',
            'udadvertiser_id.required' => '广告主ID不能为空'
        ]);
        return (new udadvertService())->updateAdvert($redisArr);
    }

    public function getCategory(Request $request)
    {
        return (new udadvertService())->getCategory();
    }

    public function autoadvertLogsList(Request $request)
    {
        return (new udadvertService())->getCategory();
    }

    public function oncesIndex(CommonPageRequest $request)
    {
        return (new udadvertService())->oncesIndex($request->only([
            'page',
            'limit',
            'plan_name'
        ]));
    }

    public function getOncesDetail(Request $request)
    {
        return (new udadvertService())->oncesDetail($request->get('id'));
    }

    public function updateOncesUdadvert(Request $request)
    {
        $redisArr = $request->only(['dir_video_link','creative_source', 'pid', 'udcampaign_id',  'udadvertiser_id', 'id', 'creative_title_nums', 'creative_title', 'creative_label', 'creative_category', 'video_type', 'creative_type', 'is_derive_creative', 'land_link_id']);
        Validator::check($redisArr, [
            'creative_type' => 'required|string',
            'dir_video_link' => 'required|string',
            'creative_title' => 'required|string',
            'creative_label' => 'required|string',
            'udcampaign_id' => 'required|integer',
            'udadvertiser_id' => 'required|integer'
        ], [
            'creative_type.required' => '创意类型不能为空',
            'dir_video_link.required' => '视频路径不能为空',
            'creative_title.required' => '创意标题不能为空',
            'creative_label.required' => '创意标签不能为空',
            'udcampaign_id.required' => '广告组ID不能为空',
            'udadvertiser_id.required' => '广告主ID不能为空'
        ]);
        return (new udadvertService())->updateOncesAdvert($redisArr);
    }


}
