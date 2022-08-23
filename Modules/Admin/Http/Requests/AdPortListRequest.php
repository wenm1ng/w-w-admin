<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdPortListRequest extends FormRequest
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
            'account_id' => 'required|is_positive_integer',

        ];
    }
    public function messages(){
        return [
            'account_id.required' => '账户id不能为空！',
            'account_id.is_positive_integer' => '账户id参数错误！',
        ];
    }
}









