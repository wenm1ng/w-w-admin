<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-11 13:27
 */
namespace Modules\Admin\Models\Platform;
use Modules\Admin\Models\BaseApiModel;
class MaterialExportTemp extends BaseApiModel
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
    protected $table = 'material_export_temp';

}
