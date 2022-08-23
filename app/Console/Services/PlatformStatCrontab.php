<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-19 16:14
 */
namespace App\Console\Services;

use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\AccountStatisticsModel;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Services\accountstatistic\AccountStatisticService;
use Modules\Admin\Models\AccountStatistic;
use Modules\Admin\Models\AccountDateStatModel;
use Modules\Admin\Services\accountstatistic\AccountStatisticUdService;

class PlatformStatCrontab extends BaseApiService
{
    /**
     * @var false|string 统计时间
     */
    protected $statDate;
    protected $statMonth;
    protected $statYear;
    protected $statTime;
    /**
     * @var 唯一字符串
     */
    protected $uniqueStr;
    protected $logName = 'platformStat';

    public function __construct()
    {
        parent::__construct();
        $this->statTime = time();
        $this->statDate = date('Y-m-d', $this->statTime);
        $this->statMonth = date('Y-m', $this->statTime);
        $this->statYear = date('Y', $this->statTime);
        $this->uniqueStr = getRandomStr(20);
    }

    /**
     * @desc       所有统计平台数据
     * @author     文明<736038880@qq.com>
     * @date       2022-08-20 16:26
     */
    public function platformStatRun(){
        try{
            //记录智投手瞬时数据
            (new AccountStatisticService())->zts_statistics(['startDate' => $this->statDate, 'endDate' => $this->statDate, 'start' => '0'], $this->uniqueStr);
            (new AccountStatisticUdService())->statistics(['startDate' => $this->statDate, 'endDate' => $this->statDate], $this->uniqueStr);
            $this->addStatData();
        }catch (\Throwable $e){
            $this->log($this->logName, $e->getMessage().'_'.$e->getFile().'_'.$e->getLine());
        }
//        $this->addStatData();
    }

    protected function addStatData(){
        //智投手统计
        $this->pitcherStat();
        //UD统计
        $this->udStat();
    }

    protected function pitcherStat(){
        $this->publicStat(1);
    }

    protected function udStat(){
        $this->publicStat(2);
    }

    /**
     * @desc       智投手统计入库
     * @author     文明<736038880@qq.com>
     * @date       2022-08-20 16:26
     */
    protected function publicStat(int $type){
        $fields = 'advertiser_id,advertiser_name,account_id,account_name,type,media_id,cost,money,roi,unique_str';
        $list = AccountStatistic::query()->where('type', $type)->select(DB::raw($fields))->get()->toArray();
        $timeData = [
            'stat_year' => $this->statYear,
            'stat_month' => $this->statMonth,
            'stat_date' => $this->statDate,
        ];
        $zeroData = $timeData + [
                'type' => $type,
                'media_id' => 0,
                'cost' => 0,
                'money' => 0,
                'roi' => 0
            ];
        $statList = [];
        $num = 0;
        $count = count($list);
        AccountDateStatModel::query()->where('stat_date', $this->statDate)->where('type', $type)->delete();
        foreach ($list as $k => $val) {
            if($val['unique_str'] === $this->uniqueStr){
                //这次操作的统计数据
                unset($val['unique_str']);
                $statList[] = $val + $timeData;
            }else{
                //为0数据
                $statList[] = $zeroData + [
                        'advertiser_id' => $val['advertiser_id'],
                        'advertiser_name' => $val['advertiser_name'],
                        'account_id' => $val['account_id'],
                        'account_name' => $val['account_name'],
                    ];
            }
            $num++;
            if($num === $count || $num == 200){
                AccountDateStatModel::query()->insert($statList);
                $num = 0;
                $statList = [];
            }
        }
    }
}
