<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-08 13:13
 */
namespace Modules\Admin\Models\Platform;
use Modules\Admin\Models\BaseApiModel;
class AuthThird extends BaseApiModel
{
    /**
     * @var string 主键id
     */
    protected $primaryKey = 'id';
    /**
     * @var bool 是否自增
     */
    public $incrementing = true;
    /**
     * @var string 主键类型
     */
    protected $keyType = 'int';

    /**
     * @var string 表名
     */
    protected $table = 'auth_third';

    public function platform(){
        return $this->belongsTo('Modules\Admin\Models\Platform\Platform','platform_id','id');
    }

    /**
     * @name  关联权限组表   多对一
     * @description   主表id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function llthird()
    {
        return $this->hasMany('Modules\Admin\Models\OncesAdvertiser','third_id','id');
    }

}
