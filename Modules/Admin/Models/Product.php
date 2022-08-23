<?php
/*
 * @desc       商品表Model
 * @author     文明<736038880@qq.com>
 * @date       2022-07-08 14:30
 */
namespace Modules\Admin\Models;
class Product extends BaseApiModel
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
