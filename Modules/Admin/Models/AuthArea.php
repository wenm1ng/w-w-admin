<?php

namespace Modules\Admin\Models;

class AuthArea extends BaseApiModel
{
	/**
	 * @name 更新时间为null时返回
	 * @description
	 * @author 西安咪乐多软件
	 * @date 2021/6/21 16:33
	 * @param value int  $value
	 * @return Boolean
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }

}
