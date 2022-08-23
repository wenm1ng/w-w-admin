<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends BaseApiModel
{

    use SoftDeletes;



    public function userList()
    {
        return $this->hasMany('Modules\Admin\Models\AuthAdmin','department_id','id');
    }




}
