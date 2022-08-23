<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-09 10:21
 */
namespace Modules\Admin\Services\inside;


use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Brand;
use Modules\Admin\Models\Category;
use Modules\Admin\Models\Tag;
use Modules\Admin\Models\MaterialTagLink;
use Modules\Admin\Models\Material;
use Modules\Admin\Models\Platform\MaterialExportTemp;
use Modules\Admin\Models\AuthAdmin;
use Modules\Admin\Models\Department;
use Modules\Admin\Services\Config;

use Illuminate\Http\Request as Requests;

class InsideService extends BaseApiService
{
    /**
     * @desc       保存视频信息
     * @author     文明<736038880@qq.com>
     * @date       2022-08-09 11:42
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveVideoInfo(array $params)
    {
        $info = MaterialExportTemp::query()->where('id', $params['id'])->first();
        if(empty($info)){
            $this->apiError('该素材不存在');
        }
        $info = $info->toArray();
        $categoryId = $brandId = $userId = $departmentId = $tagId = 0;
        try{
            DB::beginTransaction();
            //添加分类
            if (!empty($info['category_name'])) {
                $categoryId = Category::query()->where('name', $info['category_name'])->where('parant_id', 0)->value('id');
                if (empty($categoryId)) {
                    $categoryId = Category::query()->insertGetId([
                        'name' => $info['category_name'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            //添加品牌
            if (!empty($info['brand'])) {
                $brandId = Brand::query()->where('name', $info['brand'])->value('id');
                if (empty($brandId)) {
                    Brand::query()->insert([
                        'name' => $info['brand'],
                        'desc' => '',
                        'cat_id' => $categoryId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            if(!empty($info['sign'])){
                $tagId = Tag::query()->where('name', $info['sign'])->value('id');
                if(empty($tagId)){
                   $tagId = Tag::query()->insert([
                       'name' => $info['sign'],
                   ]);
                }

            }

//            $userInfo = AuthAdmin::query()->where('name', $params['user_name'])->first(['id', 'department_id']);
//            if(empty($userInfo)){
//                $userId = AuthAdmin::query()->insertGetId([
//                    'name' => $params['user_name'],
//                    'username' => $params['user_name'],
//                    'password' => bcrypt('123456'),
//                    'post_name' => '投手',
//                ]);
//            }else{
//                $userInfo = $userInfo->toArray();
//                $userId = $userInfo['id'];
//                $departmentId = Department::query()->where('id', $userInfo['department_id'])->select(DB::raw("SUBSTRING_INDEX(path, ',', 1) as parent_id"))->value('parent_id');
//            }

            //是否成片 1成片 2素材
            $materialData = [
                'type' => 1,
                'url' => call_user_func(function()use($params){
                    $str = Config::FILE_ROOT.str_replace('\\','/', str_replace(Config::SHARED_FILE_ROOT, '', $params['url']));
                    return preg_replace("/video\d+/", 'video', $str);
                }),
                'department_id' => !empty($departmentId) ? $departmentId : 0,
                'origin_url' => $info['origin_url'],
                'user_id' => $userId,
                'remark' => $info['user_name'],
                'user_name' => $info['user_name'],
                'category' => $info['category'],
                'scope' => $info['scope'],
                'name' => $info['name'],
                'file_md5' => !empty($params['file_md5']) ? $params['file_md5'] : ''
            ];
//            if (!empty($params['date_time'])) {
//                $materialData['created_at'] = $params['date_time'];
//                $materialData['updated_at'] = $params['date_time'];
//            }
            if(!empty($info['product_name'])){
                $materialData['product_name'] = $info['product_name'];
            }

            $materialId = Material::query()->insertGetId($materialData);
            if(!empty($tagId)){
                MaterialTagLink::query()->insert([
                    'material_id' => $materialId,
                    'tag_id' => $tagId
                ]);
            }

            //记录旧素材表
            MaterialExportTemp::query()->where('id', $params['id'])->update(['status' => 1, 'url' => $materialData['url']]);
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            $this->apiError($e->getMessage());
        }


        return $this->apiSuccess();
    }

    /**
     * @desc       旧素材导入
     * @author     文明<736038880@qq.com>
     * @date       2022-08-11 13:37
     * @param Requests $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function importOldMaterial(Requests $request){
        ini_set('memory_limit',-1);
        $file = $request->file('file');
        if(empty($file)){
            $this->apiError('请上传excel文件');
        }
        header("Content-type:text/html;charset=utf-8");
        require base_path('Modules/Common/lib/PHPExcel').'/Classes/PHPExcel/IOFactory.php';

        $path = $file->getPath(). "\\".$file->getFilename();
        $obj_php_excel = \PHPExcel_IOFactory::load($path);
        $sheet = $obj_php_excel->getSheet(0)->toArray();
        //0姓名 1手机号 2所属部门 3岗位 4登录账号 5权限组
        $insertData = [];
        foreach ($sheet as $key => $user) {
            if(!$key){
                continue;
            }
            if(empty($user[0])){
                break;
            }
            $insertData[] = [
                'sign' => '',
                'name' => call_user_func(function()use($user){
                    $str = trim(strrchr($user[9], '\\'),'\\');
                    $suffix = strrchr($user[9], '.');
                    $str = substr($str,0, strpos($str, $suffix));
                    if(strlen($str) > 100){
                        $str = mb_substr($str, 0, 50);
                    }
                    return $str;
                }),
                'product_name' => $user[1] ?? '',
                'brand' => $user[3] ?? '',
                'category_name' => $user[4] ?? '',
                'user_name' => $user[7] ?? '',
                'scope' => $user[6] ?? '',
                'category' => strpos($user[5], '成片') !== false ? 1 : 2,
                'origin_url' => $user[9] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $insertData = array_chunk($insertData, 200);
        foreach ($insertData as $data) {
            MaterialExportTemp::query()->insert($data);
        }

        return $this->apiSuccess();
    }
}
