<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 17:11
 */
namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class OncesAdvertiser extends BaseApiModel
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
    protected $table = 'onces_advertisers';

    /**
     * @name  关联权限组表   多对一
     * @description   主表id
     * @author 西安咪乐多软件
     * @date 2021/6/12 3:12
     **/
    public function third()
    {
        return $this->belongsTo('Modules\Admin\Models\Platform\AuthThird','third_id','id');
    }

    public function third_accounts()
    {
        return $this->belongsTo('Modules\Admin\Models\Platform\AuthThird','account_id','account_id');
    }
}
