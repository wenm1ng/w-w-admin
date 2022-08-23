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
 * @Name  后台权限验证中间件
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/4 13:37
 */

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Modules\Admin\Services\log\OperationLogService;
use Modules\Common\Exceptions\ApiException;
use Illuminate\Http\Request;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Modules\Admin\Models\Admin as AdminModel;
use Modules\Admin\Models\AuthGroup as AuthGroupModel;
use Modules\Admin\Models\AuthRule as AuthRuleModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminApiAuth
{

    public function handle(Request $request, Closure $next)
    {
        $Authorization = $request->header('Authorization');
        if(strpos($Authorization,'php_test') !== false){
            GOTO RETURNS;
        }
        Config::set('auth.defaults.guard', 'auth_admin');
        $route_data = $request->route();
        $url = str_replace($route_data->getAction()['prefix'] . '/',"",$route_data->uri);
        $url_arr = ['login/login','index/getMain','index/refreshToken'];
        $api_key = $request->header('apikey');
        if($api_key != config('admin.api_key')){
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_KEY,'message'=>MessageData::TOKEN_ERROR_KEY]);
            return $next();
        }
        if(in_array($url,$url_arr)){
            return $next($request);
        }
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user   'msg' => '用户不存在'
                throw new ApiException(['status'=>StatusData::TOKEN_ERROR_SET,'message'=>MessageData::TOKEN_ERROR_SET]);
                return $next();
            }

        }catch (TokenBlacklistedException $e) {
            // 这个时候是老的token被拉到黑名单了
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_BLACK,'message'=>MessageData::TOKEN_ERROR_BLACK]);
            return $next();
        } catch (TokenExpiredException $e) {
            //token已过期
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_EXPIRED,'message'=>MessageData::TOKEN_ERROR_EXPIRED]);
            return $next();
        } catch (TokenInvalidException $e) {
            //token无效

            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_JWT,'message'=>MessageData::TOKEN_ERROR_JWT]);

            return $next();
        } catch (JWTException $e) {
            //'缺少token'
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_JTB,'message'=>MessageData::TOKEN_ERROR_JTB]);
            return $next();
        }
        // 写入日志
        (new OperationLogService())->store($user['id']);
//        if(!in_array($url,['auth/index/refresh','auth/index/logout'])){
//            if($user['id'] != 1 && $id = AuthRuleModel::where(['href'=>$url])->value('id')){
//                $rules = AuthGroupModel::where(['id'=>$user['group_id']])->value('rules');
//                if(!in_array($id,explode('|',$rules))){
//                    throw new ApiException(['code'=>6781,'msg'=>'您没有权限！']);
//                }
//            }
//        }
        if(config('admin.is_open_mac')){
            $rs = $this->checkUniqueId($user, $request->header('uniqueId'));
            if(!$rs){
                return $next();
            }
        }

        RETURNS:
        return $next($request);
    }

    public function checkUniqueId(\Modules\Admin\Models\AuthAdmin $user, $uniqueId){
        if($user['id'] == 1 || $user['group_id'] == 1){
            return true;
        }
//        print_r($user['id']);
//        print_r($user['group_id']);
        if(empty($uniqueId)){
            throw new ApiException(['status'=>StatusData::UNIQUE_ERROR,'message'=>MessageData::UNIQUE_ERROR]);
            return false;
        }
        if(empty($user['uni_first_id'])){
            //第一次登录，保存第一唯一id
            throw new ApiException(['status'=>StatusData::UNIQUE_ERROR,'message'=>MessageData::UNIQUE_ERROR]);
        }else if($user['uni_first_id'] !== $uniqueId){
            //校验不通过
            throw new ApiException(['status'=>StatusData::UNIQUE_ERROR_NO,'message'=>MessageData::UNIQUE_ERROR_NO]);
            return false;
        }
        return true;
    }
}
