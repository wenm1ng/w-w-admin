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

namespace Modules\BigData\Services\dashboard;


use Modules\BigData\Models\AuthProject;
use Modules\BigData\Models\BigDataArticle;
use Modules\BigData\Models\BigDataArticleLike;
use Modules\BigData\Models\BigDataArticlePv;
use Modules\BigData\Models\BigDataUserInfo;
use Modules\BigData\Services\BaseApiService;
use Modules\BigData\Models\BrandAccountReport;
class DashboardService extends BaseApiService
{
    public function index(){
        $list = [
            'bigdata_article_count'=>BrandAccountReport::where(['status'=>1])->count(),
            'auth_project_name'=>BrandAccountReport::where(['status'=>1])->count(),
            'bigdata_article_pv_count'=>BrandAccountReport::where(['status'=>1])->count(),
            'bigdata_user_info_count'=>BrandAccountReport::where(['status'=>1])->count(),
            'bigdata_article_like_count'=>BrandAccountReport::where(['status'=>1])->count()
        ];
        return $this->apiSuccess('',$list);
    }
}
