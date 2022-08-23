<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 17:11
 */
namespace Modules\Event\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class BwdEvent extends BaseApiModel
{
    use HasFactory, Notifiable;
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
    protected $table = 'bwd_events';

    /**
     * @name 更新时间为null时返回
     * @description
     * @author 西安咪乐多软件
     * @date 2021/6/21 16:33
     * @param value String  $value
     * @return Boolean
     **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }

    /**
     * @name  关联权限组表   多对一
     * @description      发布人id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function bwd_issuer()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthAdmin','bwd_issuerid','id');
    }

    /**
     * @name  关联权限组表   多对一
     * @description     指定设计师id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function bwd_assdesigner()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthAdmin','bwd_assdesignerid','id');
    }

    /**
     * @name  关联权限组表   多对一
     * @description      实际设计师id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function bwd_designer()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthAdmin','bwd_designerid','id');
    }

}
