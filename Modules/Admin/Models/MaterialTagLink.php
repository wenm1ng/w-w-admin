<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-07-20 17:11
 */
namespace Modules\Admin\Models;
class MaterialTagLink extends BaseApiModel
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
    protected $table = 'material_tag_link';

}
