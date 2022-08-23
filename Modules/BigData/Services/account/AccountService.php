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

namespace Modules\BigData\Services\account;


use Modules\BigData\Models\BrandAccountReport;
use Modules\BigData\Services\BaseApiService;

class AccountService extends BaseApiService
{
    /**
     * @name 列表
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.nickname String 昵称
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.created_at Array 创建时间
     * @param  data.updated_at Array 更新时间
     * @return JSON
     **/
    public function index(array $data)
    {
        $model = BrandAccountReport::query();
        $model = $this->queryCondition($model,$data,'name');
        if (isset($data['name'])){
            $model = $model->where('name','like','%'.$data['name'].'%');
        }
        $list = $model->orderBy('id','asc')
            ->paginate($data['limit'])
            ->toArray();
        //var_dump($list);die;
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @name 添加
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:29
     * @method  POST
     * @param  data Array 添加数据
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.password String 项目地址
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function store(array $data)
    {
        return $this->commonCreate(BrandAccountReport::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function edit(int $id){
        return $this->apiSuccess('',BrandAccountReport::find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 会员id
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(BrandAccountReport::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 会员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(BrandAccountReport::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 会员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function delete(int $id){
        return $this->commonDestroy(BrandAccountReport::query(),[$id]);

    }
}
