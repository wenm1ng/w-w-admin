<?php

namespace Modules\Admin\Models;
class AdPlanStatistic extends BaseApiModel
{
	/**
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
}
