<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Models\traits\DateFormat;

class Advert extends Model
{
    use SoftDeletes, DateFormat;

    protected $fillable = [
        'admin_id', 'group_id', 'campaign_id', 'creative_id',
        'platform', 'advertiser_id', 'name', 'status', 'platform', 'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
