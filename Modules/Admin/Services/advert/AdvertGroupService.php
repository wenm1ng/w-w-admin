<?php

namespace Modules\Admin\Services\advert;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\AdvertGroup;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\traits\CurdTrait;

class AdvertGroupService extends BaseApiService
{
    use CurdTrait;

    protected Model $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model ?? new AdvertGroup;
        parent::__construct();
    }
}
