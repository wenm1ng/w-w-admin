<?php

namespace Modules\BigData\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonAlertSetting extends FormRequest
{


    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required',
            'field_name' => 'required',
            'values' => 'required',
        ];
    }
    public function messages(){
        return [
            'name.required' 				=> '缺少参数（name）！',
            'field_name.required' 	=> '缺少参数(field_name)！',
            'values.required' => '缺少参数(values)！'
        ];
    }
}









