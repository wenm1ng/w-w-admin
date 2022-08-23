<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 17:11
 */
namespace Modules\Event\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class BwdRemark extends BaseApiModel
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
    protected $table = 'bwd_remarks';

    /**
     * @name  关联权限组表   多对一
     * @description   主表id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function bwd()
    {
        return $this->belongsTo('Modules\Event\Models\BwdEvent','bwdid','id');
    }
}
