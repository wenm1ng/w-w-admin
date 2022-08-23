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
 * @Name 文章收藏模型
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/7/2 15:57
 */

namespace Modules\BlogApi\Models;


class BlogArticleCollect extends BaseApiModel
{
    /**
     * @name 更新时间为null时返回
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/28 13:20
     * @param String  $value
     * @return String
     **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
    /**
     * @name  关联文章表   多对一
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function article_to()
    {
        return $this->belongsTo('Modules\BlogApi\Models\BlogArticle','article_id','id');
    }
    /**
     * @name  关联用户表   多对一
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function user_to()
    {
        return $this->belongsTo('Modules\BlogApi\Models\BlogUserInfo','user_id','id');
    }
}
