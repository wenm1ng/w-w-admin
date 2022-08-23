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
 * @Name 用户登录服务
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/11 16:50
 */

namespace Modules\Admin\Services\auth;


use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\AuthAdmin as AuthAdminModel;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\StatusData;
use Modules\Common\Exceptions\MessageData;
use Illuminate\Http\Request;

class LoginService extends BaseApiService
{
    /**
     * @name 用户登录
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/11 16:53
     * @param data  Array 用户信息
     * @param data.username String 账号
     * @param data.password String 密码
     * @return JSON
     **/
    public function login(Request $request){
        $data = $request->only(['username','password']);
        if (true == \Auth::guard('auth_admin')->attempt($data)) {
            $userInfo = AuthAdminModel::where(['username'=>$data['username']])->select('id','username','uni_first_id','group_id')->first();
            if($userInfo){
                $user_info = $userInfo->toArray();
                $user_info['password'] = $data['password'];
                if(config('admin.is_open_mac')){
                    $this->checkUniqueId($user_info, $request);
                }
                $token = (new TokenService())->setToken($user_info);
                return $this->apiSuccess('登录成功！',$token);
            }
        }
        $this->apiError('账号或密码错误！');
    }

    public function checkUniqueId(array &$user_info, Request $request){
        if($user_info['id'] == 1 || $user_info['group_id'] == 1){
            return;
        }
        $uniqueId = $request->header('uniqueId');
        if(empty($uniqueId)){
            throw new ApiException(['status'=>StatusData::UNIQUE_ERROR,'message'=>MessageData::UNIQUE_ERROR]);
        }
        if(empty($user_info['uni_first_id'])){
            //第一次登录，保存第一唯一id
            AuthAdminModel::query()->where('id',$user_info['id'])->update(['uni_first_id' => $uniqueId]);
            $user_info['uni_first_id'] = $uniqueId;
        }else if($user_info['uni_first_id'] !== $uniqueId){
            //校验不通过
            throw new ApiException(['status'=>StatusData::UNIQUE_ERROR_NO,'message'=>MessageData::UNIQUE_ERROR_NO]);
        }
    }
}
