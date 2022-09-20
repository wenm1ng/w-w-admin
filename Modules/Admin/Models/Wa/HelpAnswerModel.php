<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-19 15:10
 */
namespace Modules\Admin\Models\Wa;
use Modules\Admin\Models\BaseApiModel;
class HelpAnswerModel extends BaseApiModel
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
    protected $table = 'help_answer';

    /**
     * @var string 连接名
     */
    protected $connection = 'mysql_api';

}
