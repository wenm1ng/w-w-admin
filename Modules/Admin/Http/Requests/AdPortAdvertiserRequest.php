<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdPortAdvertiserRequest extends FormRequest
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
            'advertiser_id' => 'required|is_positive_integer',

        ];
    }
    public function messages(){
        return [
            'advertiser_id.required' => '子账户id不能为空！',
            'advertiser_id.is_positive_integer' => '子账户id参数错误！',
        ];
    }
}









