<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class SecUser extends BaseApiModel
{

    use SoftDeletes;


    public function getAdminUser()
    {
        return $this->hasOne('Modules\Admin\Models\AuthAdmin','id','user_id');
    }


    public function getPlatform()
    {
        return $this->hasOne('Modules\Admin\Models\Platform\Platform','id','platform_id');
    }

}
