<?php

namespace Modules\Admin\Models;
class BusinessTeam extends BaseApiModel
{
	/**
	 * @name 更新时间为null时返回
	 * @description
	 * @author 西安咪乐多软件
	 * @date 2021/6/14 9:33
     * @param  $value Int
	 * @return Boolean
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
}
