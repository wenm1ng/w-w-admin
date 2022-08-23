<?php

namespace Modules\Admin\Services\advert;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\AdvertCampaign;
use Modules\Admin\Models\AdvertCreative;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\traits\CurdTrait;

class AdvertCreativeService extends BaseApiService
{
    use CurdTrait;

    protected Model $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model ?? new AdvertCreative;
        parent::__construct();
    }
}
