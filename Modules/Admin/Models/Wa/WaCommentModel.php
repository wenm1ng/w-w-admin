<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 15:44
 */
namespace Modules\Admin\Models\Wa;
use Modules\Admin\Models\BaseApiModel;
class WaCommentModel extends BaseApiModel
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
    protected $table = 'wa_comment';

    /**
     * @var string 连接名
     */
    protected $connection = 'mysql_api';

}
