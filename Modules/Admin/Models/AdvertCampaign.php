<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Models\traits\DateFormat;

class AdvertCampaign extends Model
{
    use SoftDeletes, DateFormat;

    protected $fillable = [
        'admin_id', 'platform', 'platform_id', 'name', 'data', 'status'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
