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
 * @Name
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/7/3 17:10
 */

namespace Modules\Event\Services\dashboard;


//use Modules\Event\Models\AuthProject;
//use Modules\Event\Models\EventArticle;
//use Modules\Event\Models\EventArticleLike;
//use Modules\Event\Models\EventArticlePv;
//use Modules\Event\Models\EventUserInfo;
use Modules\Event\Services\BaseApiService;
//use Modules\Event\Models\BrandAccountReport;
class DashboardService extends BaseApiService
{
    public function index(){
        $list = [
//            'bigdata_article_count'=>1,
//            'auth_project_name'=>2,
//            'bigdata_article_pv_count'=>3,
//            'bigdata_user_info_count'=>4,
//            'bigdata_article_like_count'=>5
        ];
        return $this->apiSuccess('',$list);
    }
}
