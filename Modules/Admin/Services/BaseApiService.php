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
 * @Name 当前模块服务基类
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/11 16:52
 */

namespace Modules\Admin\Services;

use Modules\Common\Services\BaseService;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class BaseApiService extends BaseService
{
    /**
     * @desc       过滤数组
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 9:39
     * @param array  $data
     * @param string $string
     *
     * @return array
     */
    public function filterArr(array $data, $string = ''){
        $arr = array_flip(explode(',', $string));

        $newData = [];
        foreach ($data as $key => $val) {
            if(isset($arr[$key])){
                $newData[$key] = $val;
            }
        }
        return $newData;
    }

    /**
     * @desc       设置多字段唯一枚举
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 9:57
     * @param array  $list
     * @param string ...$keys
     *
     * @return array
     */
    public function setEnumArr(array $list, string ...$keys){
        $newList = [];
        foreach ($list as $val) {
            $str = '';
            foreach ($keys as $column) {
                $str .= '_'.$val[$column];
            }
            $str = ltrim($str, '_');
            $newList[$str] = $val;
        }
        return $newList;
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

    public static function arrayGroup(array $arr, $groupKey, $beValKey = '')
    {
        $return = [];
        if ($beValKey) {
            foreach ($arr as $key => $val) {
                $return[$val[$groupKey]][] = $val[$beValKey];
            }
        } else {
            foreach ($arr as $key => $val) {
                $return[$val[$groupKey]][] = $val;
            }
        }
        return $return;
    }

    /**
     * @desc       获取文件上传文件夹名称
     * @author     文明<736038880@qq.com>
     * @date       2022-08-06 14:05
     * @param string $extendName
     *
     * @return string
     */
    public static function getFileDir(string $extendName){
        $imageStr = ',jpg,png,jpeg,gif,tiff,psd,raw,eps,svg,pdf,bmp';
        $videoStr = ',mp4,avi,mpeg,wmv,mov';
        if(strpos($imageStr, ','.$extendName) !== false){
            return 'image';
        }elseif(strpos($videoStr, ','.$extendName) !== false){
            return 'video';
        }
        return 'file';
    }
}
