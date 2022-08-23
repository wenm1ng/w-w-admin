<?php

namespace Modules\Admin\Models;
class UdAutomation extends BaseApiModel
{
	/**
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
}
