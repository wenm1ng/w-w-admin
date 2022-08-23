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
 * @Name 控制器基类
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/11 15:11
 */

namespace Modules\Common\Controllers;
use Illuminate\Routing\Controller;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
class BaseController extends Controller
{
    public function __construct(){

    }

    /**
     * @desc       分开文件记录日志
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:24
     * @param string $fileName
     * @param string $message
     * @param array  $data
     */
    public function log(string $fileName, string $message, array $data = []){
        (new Logger('local'))
            ->pushHandler(new RotatingFileHandler(storage_path('logs/'.date('Y-m-d').'/'.$fileName.'.log')))
            ->info($message, $data);
    }

}
