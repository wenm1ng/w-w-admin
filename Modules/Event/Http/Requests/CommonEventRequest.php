<?php

namespace Modules\Event\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonEventRequest extends FormRequest
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
            'bwd_flag' => 'required',
            'id' => 'required|is_positive_integer',
//            'eapectdate'=>'required'
        ];
    }
    public function messages(){
        return [
//            'eapectdate.required' 				=> '缺少参数（eapectdate）！',
            'bwd_flag.required' 				=> '缺少参数（bwd_flag）！',
            'id.required' 				=> '缺少参数（id）！',
            'id.is_positive_integer' 		=> '（id）参数错误！',
        ];
    }
}









