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
 * @Name 当前模块控制器基类
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/28 13:12
 */

namespace Modules\BigData\Http\Controllers\v1;


use Modules\Common\Controllers\BaseController;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\StatusData;

class BaseApiController extends BaseController
{
    public function __construct(){
        parent::__construct();
    }
    public function apiResponse($func){
        try{
            if (!is_callable($func)){
                throw new \Exception('Argument is not an executable function!');
            }
            return call_user_func($func);
        }catch (\Exception $e){
            throw new ApiException(['status' => StatusData::BAD_REQUEST, 'message' => $e->getMessage()]);
        }catch (\Throwable $e){
            $this->log('apiResponse', $e->getMessage().'_'.$e->getFile().'_'.$e->getLine());
            throw new ApiException(['status' => StatusData::BAD_REQUEST, 'message' => '系统异常']);
        }
    }
}
