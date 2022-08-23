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
 * @Name  图片上传服务
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/12 01:41
 */

namespace Modules\Admin\Services\upload;


use Illuminate\Http\Request;
use Modules\Admin\Models\AuthConfig as AuthConfigModel;
use Modules\Admin\Models\AuthImage as AuthImageModel;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\Config;

class ImageService extends BaseApiService
{
    /**
     * @name  图片上传
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 1:36
     * @param request Request 图片资源完整信息
     * @param request.file Resource  图片资源
     * @return JSON
     **/
    public function fileImage(Request $request)
    {
        if ($request->isMethod('POST')){
            $fileCharater = $request->file('file');
            if ($fileCharater->isValid()){
                $imageStatus = AuthConfigModel::where('id',1)->value('image_status');
//                if($imageStatus == 1){
                $dir = $this->getFileDir($fileCharater->getClientOriginalExtension());
                $uploadPath = $dir.'/'.date('Ymd');
                $path = $request->file('file')->store($uploadPath,'upload');
                if ($path){
                    $url = Config::FILE_ROOT.'/'.$path;
                }
//                }else if($imageStatus == 2){
//                    $url = $this->addQiniu($fileCharater);
//                }
                if(isset($url)){
                    $image_id = AuthImageModel::insertGetId([
                        'url'=>$url,
                        'open'=>$imageStatus,
                        'status'=>0,
                        'created_at'=> date('Y-m-d H:i:s')
                    ]);
                    if($image_id){
                        if($imageStatus == 1){
                            $url = $this->getHttp().$url;
                        }
                        return $this->apiSuccess('上传成功！',
                            [
                                'image_id'=>$image_id,
                                'url'=> $url,
                                'file_md5' => md5_file(env('UPLOAD_PATH').'/'.$path)
                            ]);
                    }
                }
            }
        }
        $this->apiError('上传失败！');
    }

    public function getUploadPercent(){
        session_start();
        $key = ini_get("session.upload_progress.prefix") . $_GET["key"];
        if (!empty($_SESSION[$key])) {
            $current = $_SESSION[$key]["bytes_processed"];
            $total = $_SESSION[$key]["content_length"];
            echo $current < $total ? ceil($current / $total * 100) : 100;
        }else{
            echo 100;
        }
    }
    /**
     * @name 七牛云图片上传
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 1:48
     * @method  GET
     * @param fileCharater 图片对象
     * @return JSON
     **/
    private function addQiniu(object $fileCharater)
    {
        $this->apiError('七牛云存储暂未开放！');
        // 初始化
        $disk = QiniuStorage::disk('qiniu');
        // 重命名文件
        $fileName = md5($fileCharater->getClientOriginalName().time().rand()).'.'.$fileCharater->getClientOriginalExtension();
        // 上传到七牛
        $bool = $disk->put('iwanli/image_'.$fileName,file_get_contents($fileCharater->getRealPath()));
        // 判断是否上传成功
        if($bool){
            return $disk->downloadUrl('iwanli/image_'.$fileName);
        }else{
            return false;
        }
    }

    /**
     * @name 图片列表
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/12 2:20
     * @param data Array 查询相关参数
     * @param data.page int 页码
     * @param data.limit int 每页显示条数
     * @return JSON
     **/
    public function getImageList(array $data){
        $model = AuthImageModel::query();
        $list = $model->select('id','open','url')->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();

        $http = $this->getHttp();
        foreach($list['data'] as $k=>$v){
            $list['data'][$k]['status'] = false;
            if($v['open'] == 1){
                $list['data'][$k]['url'] = $http . $v['url'];
            }else{
                $list['data'][$k]['url'] = $v['image_one']['url'];
            }
        }
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }
}
