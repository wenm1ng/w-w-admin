<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-09 13:32
 */
namespace Modules\Common\Validator;

use Illuminate\Support\Facades\Validator as BaseValidator;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\StatusData;

/**
 * 验证器助手，基于框架
 */
class Validator extends BaseValidator
{
    /**
     * @desc       检查参数，验证不通过直接抛出错误结果
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 13:36
     * @param array $data 需要检查的参数数组
     * @param array $rules 检查规则
     * @param array $messages 错误提示语
     * @param array $customAttributes
     * @return void
     */
    public static function check(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = parent::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            throw new ApiException([
                'status' => StatusData::BAD_REQUEST,
                'message'=> $validator->errors()->first()
            ]);
        }
    }
}
