<?php

return [
    'oncesapi' => [
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
    ]
];
