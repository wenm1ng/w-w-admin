<?php

namespace Modules\Admin\Services\advert;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Advert;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\traits\CurdTrait;

class AdvertService extends BaseApiService
{
    use CurdTrait;

    protected Model $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model ?? new Advert;
        parent::__construct();
    }
}
