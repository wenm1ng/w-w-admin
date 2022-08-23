<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-16 17:30
 */
namespace Modules\Admin\Models;
class AccountStatistic extends BaseApiModel
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

    protected $fillable = ['cost','advertiser_name','roi','money','remark','ymd'];//这里是可以批量赋值的属性

    /**
     * @var string 表名
     */
    protected $table = 'account_statistics';
}
