<?php
/*
 * @desc       内部调用控制器
 * @author     文明<736038880@qq.com>
 * @date       2022-08-09 10:14
 */
namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Services\inside\InsideService;
use Modules\Common\Validator\Validator;

class InsideController extends BaseApiController
{
    public function saveVideoInfo(Request $request)
    {
        $params = $request->all();
        Validator::check($params, [
            'id' => 'required|integer',
            'url' => 'required|string',
        ], [
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须为int类型',
            'url.required' => '链接不能为空',
            'url.string' => '链接为string类型',
        ]);

        return $this->apiResponse(function()use($request){
            return (new InsideService())->saveVideoInfo($request->all());
        });
    }

    /**
     * @desc       旧素材导入
     * @author     文明<736038880@qq.com>
     * @date       2022-08-11 13:41
     * @param Request $request
     *
     * @return mixed
     */
    public function importOldMaterial(Request $request){
        return $this->apiResponse(function()use($request){
            return (new InsideService())->importOldMaterial($request);
        });
    }
}
