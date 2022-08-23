<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonKeysignRequest extends FormRequest
{
    /**
     * php artisan module:make-request AdminRequest Admin
     */

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'id' 		=> 'required|is_positive_integer',
            'key_sign' 	=> 'required',

        ];
    }
    public function messages(){
        return [
            'id.required' 					=> '缺少参数（id）！',
            'id.is_positive_integer' 		=> '（id）参数错误！',
            'key_sign.required' 				=> '缺少参数（key_sign）！',
        ];
    }
}









