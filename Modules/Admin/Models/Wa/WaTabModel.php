<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-24 17:31
 */
namespace Modules\Admin\Models\Wa;
use Modules\Admin\Models\BaseApiModel;
class WaTabModel extends BaseApiModel
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
    protected $table = 'wa_tab';

    /**
     * @var string 连接名
     */
    protected $connection = 'mysql_api';

}
