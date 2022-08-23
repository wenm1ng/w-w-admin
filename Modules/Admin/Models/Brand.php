<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends BaseApiModel
{

    use SoftDeletes;



    public function getCate()
    {
        return $this->hasOne('Modules\Admin\Models\Category','id','cat_id');
    }

    public function getimg()
    {
        return $this->hasOne('Modules\Admin\Models\AuthImage','id','logo_id');
    }





}
