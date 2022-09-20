<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-09-17 18:01
 */
namespace Modules\Admin\Models\Wa;
use Modules\Admin\Models\BaseApiModel;
use Illuminate\Support\Facades\DB;

class LeaderBoardModel extends BaseApiModel
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
    protected $table = 'leader_board';

    /**
     * @var string 连接名
     */
    protected $connection = 'mysql_api';

    public static function incrementScore($userId, int $type, string $dateTime, int $num = 1, int $descriptionNum = 0){
        //如果描述字数大于0，说明是回答，需要>=15才予积分
        if($descriptionNum > 0 && $descriptionNum < 15){
            $descriptionNum = 0;
        }
        $timeData = getWowWeekYear($dateTime);
        $year = $timeData['year'];
        $week = $timeData['week'];
        $model = self::query();
        $id = $model
            ->where('year', $year)
            ->where('week', $week)
            ->where('user_id', $userId)
            ->value('id');

        $typeColumnLink = [
            1 => 'adopt_num', //采纳
            2 => 'answer_num' //回答
        ];
        $column = $typeColumnLink[$type];
        $scoreLink = [
            1 => 3,
            2 => 1
        ];
        $value = $scoreLink[$type];
        $score = $value * $num;
        $descriptionNum = $descriptionNum * $num;
        if(empty($id) && $num >= 1){
            //没有记录，添加
            $insertData = [
                'year' => $year,
                'week' => $week,
                'user_id' => $userId,
                'score' => $score,
                'description_num' => $descriptionNum
            ];
            $insertData[$column] = $value;
            $model->insert($insertData);
        }else{
            //increment
            $updateData = [
                $column => DB::raw("{$column} + {$num}"),
                'score' => DB::raw("score + {$score}"),
                'description_num' => DB::raw('description_num + '.$descriptionNum)
            ];
            $model->where('id', $id)->update($updateData);
        }
    }
}
