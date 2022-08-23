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
 * @Name 管理员服务
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/12 03:07
 */

namespace Modules\Admin\Services\admin;


use http\Env\Request;
use Modules\Admin\Models\AuthAdmin as AuthAdminModel;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Http\Request as Requests;
use Modules\Admin\Models\AuthGroup;
use Modules\Admin\Models\Department;

class AdminService extends BaseApiService
{
    /**
     * @name 管理员列表
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.username String 账号
     * @param  data.group_id Int 权限组ID
     * @param  data.project_id int 项目ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.created_at Array 创建时间
     * @param  data.updated_at Array 更新时间
     * @return JSON
     **/
    public function getList(array $data)
    {
        $model = AuthAdminModel::query();
        $model = $this->queryCondition($model,$data,'username');
        if (isset($data['group_id']) && $data['group_id'] > 0){
            $model = $model->where('group_id',$data['group_id']);
        }
        if (isset($data['project_id']) && $data['project_id'] > 0){
            $model = $model->where('project_id',$data['project_id']);
        }
        $list = $model->with([
                'auth_groups'=>function($query){
                    $query->select('id','name');
                },
                'auth_projects'=>function($query){
                    $query->select('id','name');
                },
                'departments'=>function($query){
                    $query->select('id','name');
                },
            ])
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        foreach ($list['data'] as &$val) {
            $val['auth_groups'] = !empty($val['auth_groups']) ? $val['auth_groups'] : [];
            $val['auth_projects'] = !empty($val['auth_projects']) ? $val['auth_projects'] : [];
            $val['departments'] = !empty($val['departments']) ? $val['departments'] : [];
        }
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
     * @param  data.username String 账号
     * @param  data.phone String 手机号
     * @param  data.username String 账号
     * @param  data.password String 密码
     * @param  data.group_id int 权限组ID
     * @param  data.project_id int 项目ID
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->commonCreate(AuthAdminModel::query(),$data);
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
        return $this->apiSuccess('',AuthAdminModel::select('id','name','group_id','phone','username','project_id','department_id','post_name')->find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 管理员id
     * @param  daya.name String 名称
     * @param  daya.phone String 手机号
     * @param  daya.username String 账号
     * @param  daya.group_id Int 权限组ID
     * @param  data.project_id int 项目ID
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthAdminModel::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 管理员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(AuthAdminModel::query(),$id,$data);
    }
    /**
     * @name 初始化密码
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:51
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function updatePwd(int $id){
        return $this->commonStatusUpdate(AuthAdminModel::query(),$id,['password'=>bcrypt(config('admin.update_pwd'))],'密码初始化成功！','密码初始化失败，请重试！');
    }

    /**
     * @desc       导入excel用户数据
     * @author     文明<736038880@qq.com>
     * @date       2022-08-16 17:09
     * @param Requests $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Requests $request){
        $file = $request->file('file');
        if(empty($file)){
            $this->apiError('请上传excel文件');
        }
        header("Content-type:text/html;charset=utf-8");
        require base_path('Modules/Common/lib/PHPExcel').'/Classes/PHPExcel/IOFactory.php';

        $path = $file->getPath(). "\\".$file->getFilename();
        $obj_php_excel = \PHPExcel_IOFactory::load($path);
        $sheet = $obj_php_excel->getSheet(0)->toArray();
        $authLink = AuthGroup::query()->where('status', 1)->pluck('id','name')->toArray();
        $departmentLink = Department::query()->where('is_delete', 0)->pluck('id', 'name')->toArray();
        $usernameLink = AuthAdminModel::query()->pluck('id','username')->toArray();
        //0姓名 1手机号 2所属部门 3岗位 4登录账号 5权限组
        $insertData = [];
        $repeat = [];
        $password = bcrypt('123456');
        foreach ($sheet as $key => $user) {
            if(!$key){
                continue;
            }
            foreach ($user as $columnValue) {
                if(empty($columnValue)){
                    continue 2;
                }
            }
            if(isset($usernameLink[$user[4]])){
                $repeat[] = $user[4];
                continue;
            }
            $insertData[] = [
                'name' => $user[0],
                'phone' => $user[1],
                'password' => $password,
                'department_id' => $departmentLink[$user[2]] ?? 0,
                'post_name' => $user[3],
                'username' => $user[4],
                'group_id' => $authLink[$user[5]] ?? 0,
                'project_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        if(empty($insertData)){
            if(!empty($repeat)){
                $this->apiError('以下账号在系统已存在：'. implode(',', $repeat));
            }
            $this->apiError('格式有误，请检查');
        }
        $insertData = array_chunk($insertData, 200);
        foreach ($insertData as $data) {
            AuthAdminModel::query()->insert($data);
        }

        return $this->apiSuccess();
    }

    /**
     * @desc       清空设备唯一id
     * @author     文明<736038880@qq.com>
     * @date       2022-08-16 17:12
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delUniqueId($id){
        if(empty($id)){
            $this->apiError('id不能为空');
        }
        AuthAdminModel::query()->where('id', $id)->update(['uni_first_id' => '']);
        return $this->apiSuccess();
    }

    /**
     * @desc       合并管理员姓名
     * @author     文明<736038880@qq.com>
     * @date       2022-08-22 11:32
     * @param array         $list
     * @param string|string $userIdColumn
     * @param string|string $targetColumn
     */
    public function mergeAdminName(array &$list, string $userIdColumn = 'user_id', string $targetColumn = 'user_name'){
        $userIds = array_column($list, $userIdColumn);
        $userLink = AuthAdminModel::query()->whereIn('id', $userIds)->pluck('name', 'id')->toArray();
        foreach ($list as &$val) {
            $val[$targetColumn] = $userLink[$val[$userIdColumn]] ?? '';
        }
    }
}
