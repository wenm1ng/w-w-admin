<?php

namespace Modules\Admin\Models;
class AutoadvertLog extends BaseApiModel
{
	/**
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
}
