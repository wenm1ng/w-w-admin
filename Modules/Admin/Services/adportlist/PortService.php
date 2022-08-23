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


use Modules\Admin\Models\OncesAdvertiser;
use Modules\Admin\Services\BaseApiService;
use GuzzleHttp;
class PortService extends BaseApiService
{

    protected static $oncesUrl = [
        //获取纵横组织下资产账户列表
        'accountlist' => 'https://ad.oceanengine.com/open_api/2/majordomo/advertiser/select/',
        'getVideo' => 'https://ad.oceanengine.com/open_api/2/file/video/get/',
        //获取视频的智能封面
        'getVideoCover' => 'https://ad.oceanengine.com/open_api/2/tools/video_cover/suggest/',
        //自定义创意
        'customCreative' => 'https://ad.oceanengine.com/open_api/2/creative/custom_creative/create/',
        //程序化创意
        'proceduralCreative' => 'https://ad.oceanengine.com/open_api/2/creative/procedural_creative/create/',
        //动态创意词包
        'getCreativeWord' => 'https://ad.oceanengine.com/open_api/2/tools/creative_word/select/',
    ];
    /**
     * @name
     * @description
     * @author 西安咪乐多软件
     * 获取纵横组织下资产账户列表
     **/
    public function GetAccountList(array $data)
    {
        $http = new GuzzleHttp\Client;
        $response = $http->get($data['url'], [
            'headers' => $data['header'],
            'json' => $data['data'],
        ]);
        $body = $response->getBody()->getContents();
        $body = json_decode($body,true);
        return $body;
    }



}
