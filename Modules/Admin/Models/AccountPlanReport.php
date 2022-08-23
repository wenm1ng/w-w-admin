<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-16 17:30
 */
namespace Modules\Admin\Models;
class AccountPlanReport extends BaseApiModel
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
}
