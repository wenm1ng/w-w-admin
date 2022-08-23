<?php
// +----------------------------------------------------------------------
// | Name: 咪乐多管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: 西安咪乐多软件 <997786358@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name 会员管理服务
 * @Description
 * @Auther 西安咪乐多软件
 * @Date 2021/6/29 14:53
 */

namespace Modules\Admin\Services\accountstatistic;

use Modules\Admin\Models\AccountStatistic;
use Modules\Admin\Models\SystemConfig;
use Modules\Admin\Services\BaseApiService;

class AccountStatisticUdService extends BaseApiService
{
    //ud账户消耗统计数据
    public function statistics(array $data, string $uniqueStr = '')
    {
        $startTime = microtime(true);
        $list = $this->requestASmartPitcher($data);
        if (empty($list)) {
            echo '没有数据' . PHP_EOL;
            return '';
        }
        //ud的账户数据
        $adIdLink = AccountStatistic::where('type', 2)->select('advertiser_id', 'id')->get()->toArray();
        $tempArr = array_column($adIdLink, 'id', 'advertiser_id');
        $addData = [];
        $upnums = $innums = 0;
        foreach ($list as $val) {
            if (isset($tempArr[$val['advertiserId']])) {
                //更新数据
                $updateData = [
                    'cost' => round($val['cost'] / 100, 2),
                    'advertiser_name' => $val['advertiserName'] ?? '',
                    'roi' => $val['returnOnInvestment'] ?? 0,
                    'media_id' => $val['directMediaId'] ?? 0,
                    'type' => 2,
                    'unique_str' => !empty($uniqueStr) ? $uniqueStr : ''
                ];
                $upnum = AccountStatistic::where('id', $tempArr[$val['advertiserId']])->update($updateData);
                $upnums += $upnum;
            } else {
                $addData[] = [
                    'advertiser_id' => $val['advertiserId'],
                    'cost' => round($val['cost'] / 100, 2),
                    'advertiser_name' => $val['advertiserName'] ?? '',
                    'roi' => $val['returnOnInvestment'] ?? 0,
                    'media_id' => $val['directMediaId'] ?? 0,
                    'type' => 2,
                    'unique_str' => !empty($uniqueStr) ? $uniqueStr : ''
                ];
            }
        }
        if (!empty($addData)) {
            if (AccountStatistic::insert($addData)) {
                $innums = count($addData);
            }
        }
        unset($list);
        $endTime = microtime(true);
        echo '更新数量：' . ($upnums + $innums) . PHP_EOL;
        echo '耗时: ' . round($endTime - $startTime, 3) . 's' . PHP_EOL;
    }

    //ud模拟请求获取数据  不保证持续性   20220822测试时没问题
    public function requestASmartPitcher(array $data)
    {
        $cookieRes = SystemConfig::whereIn('id', [6, 7, 8])->select('key', 'value')->get()->toArray();
        $cookieKv = array_column($cookieRes, 'value', 'key');
        $bdCookie = json_decode($cookieKv['ud_bod_cookie'], true)['Cookie'];
        $anaCookie = json_decode($cookieKv['ud_all_cookie'], true)['Cookie'];
        $moCookie = json_decode($cookieKv['ud_morei_cookie'], true)['Cookie'];
        $moUrl = 'https://unidesk.taobao.com/api/direct/report/advertiser/list';

        $bdData = [
            "r" => "mx_161",
            "effect" => "7",
            "effectType" => "click",
            "keyWord" => "",
            "pageSize" => "200",
            "pageNo" => "1",
            "timeStr" => "1660986289638",
            "dynamicToken" => "432220196444396440416420",
            "bizCode" => "uniDeskRtaBrand"
        ];
        $headers = [
            'authority: unidesk.taobao.com',
            'sec-ch-ua: "Google Chrome";v="87", " Not;A Brand";v="99", "Chromium";v="87"',
            'accept: application/json, text/javascript, */*; q=0.01',
//            'x-xsrf-token: d9de7b39-b6f0-4f89-9095-3bc9aa3d7612',
            'x-requested-with: XMLHttpRequest',
            'bx-v: 2.2.2',
            'sec-ch-ua-mobile: ?0',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
            'sec-fetch-site: same-origin',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://unidesk.taobao.com/direct/index?mxredirectUrl=',
            'accept-language: zh-CN,zh;q=0.9',
//            'cookie: t=b64cd1618354cea314cafb780e3772a5, cna=s/FgG9eybzQCAaN9UpSXYf+m, lgc=tb507390670, tracknick=tb507390670, XSRF-TOKEN=e39f8a46-ddb3-4d94-a470-a15aa81ac99c, _tb_token_=eef1eba335361, xlly_s=1, uc1=pas=0&cookie21=URm48syIZxx%2F&cookie16=VFC%2Fu…D&cookie15=V32FPkk%2Fw0dUvg%3D%3D&existShop=false, csg=48192808, cancelledSubSites=empty, dnk=tb507390670, existShop=MTY2MTE0NTY3NQ%3D%3D, _cc_=W5iHLLyFfA%3D%3D, _l_g_=Ug%3D%3D, sg=04f, _nk_=tb507390670, ud_cloud_uid=c4782992e050489b5d5e617e73c0f7cd, tfstk=cMaFBOGSLwQU75r4bVgPNPQ_fS5dZeV3ZFljxt1pVv7F0bnhiJJ-s0Cq_bMASDf.., l=eBQT2HT7L52uxGSCmOfwnurza77OQIRAguPzaNbMiOCPOLCJ5WXGW6lmh3YvCnGVh67JR3JZQomBBeYBqS025Cd69gW-XcHmn, isg=BAoK7X0rXUaihdALz9Sl5lr4W_Cs-45VJ79S85RDqN3oR6oBfIhYZEU1U7Obtwbt'
        ];

        $ttData = [
            'r' => 'mx_1220',
            'effect' => '7',
            'effectType' => 'click',
            'ef' => 'hourId',
            'directMediaId' => '103',
            'advertiserId' => '',
            'pageNo' => 1,
            'pageSize' => 200,
            'orderField' => '',
            'orderBy' => '',
            'timeStr' => '1661133648149',
            'dynamicToken' => '432220208208204200196416',
            'bizCode' => 'uniDeskRtaBrand'
        ];
        $ttData['startTime'] = $data['startDate'] ?? date('Y-m-d');
        $ttData['endTime'] = $data['endDate'] ?? date('Y-m-d');
        $gdtData = [
            'r' => 'mx_1220',
            'effect' => '7',
            'effectType' => 'click',
            'ef' => 'hourId',
            'directMediaId' => '104',
            'advertiserId' => '',
            'pageNo' => 1,
            'pageSize' => 200,
            'orderField' => '',
            'orderBy' => '',
            'timeStr' => '1661133648149',
            'dynamicToken' => '432220208208204200196416',
            'bizCode' => 'uniDeskRtaBrand'
        ];
        $gdtData['startTime'] = $ttData['startTime'];
        $gdtData['endTime'] = $ttData['endTime'];
        $ksData = [
            'r' => 'mx_1220',
            'effect' => '7',
            'effectType' => 'click',
            'ef' => 'hourId',
            'directMediaId' => '105',
            'advertiserId' => '',
            'pageNo' => 1,
            'pageSize' => 200,
            'orderField' => '',
            'orderBy' => '',
            'timeStr' => '1661133648149',
            'dynamicToken' => '432220208208204200196416',
            'bizCode' => 'uniDeskRtaBrand'
        ];
        $ksData['startTime'] = $ttData['startTime'];
        $ksData['endTime'] = $ttData['endTime'];
        $bdData['startTime'] = $ttData['startTime'];
        $bdData['endTime'] = $ttData['endTime'];
        try {
            $bdHeaders = $anaHeaders = $moHeaders = $headers;
            $bdHeaders[] = 'x-xsrf-token: ' . $this->getXsrfToken($bdCookie);
            $bdHeaders[] = 'cookie: ' . $bdCookie;
            $anaHeaders[] = 'x-xsrf-token: ' . $this->getXsrfToken($anaCookie);
            $anaHeaders[] = 'cookie: ' . $anaCookie;
            $moHeaders[] = 'x-xsrf-token: ' . $this->getXsrfToken($moCookie);
            $moHeaders[] = 'cookie: ' . $moCookie;

            $bdList = $anaList = $ttList = $gdtList = $ksList = [];
            $ttRes = httpRequest($moUrl, $ttData, $moHeaders, 'GET', 'params');
            $returnTtData = json_decode($ttRes, true);
            if (!empty($returnTtData) && !empty($returnTtData['info']['message']) && str_contains($returnTtData['info']['message'], '请求非法，请先登录')) {
                $this->log('ud-account-stat', '头条请求接口错误！' . $ttRes);
                return [];
            }
            if (!empty($returnTtData) && !empty($returnTtData['info']['ok']) && $returnTtData['info']['ok'] == true) {
                $ttList = $returnTtData['data']['list'];
            } else {
                $this->log('ud-account-stat', '头条请求接口错误！' . $ttRes);
            }
            $gdtRes = httpRequest($moUrl, $gdtData, $moHeaders, 'GET', 'params');
            $returnGdtData = json_decode($gdtRes, true);
            if (!empty($returnGdtData) && !empty($returnGdtData['info']['ok']) && $returnGdtData['info']['ok'] == true) {
                $gdtList = $returnGdtData['data']['list'];
            } else {
                $this->log('ud-account-stat', '广点通请求接口错误！' . $gdtRes);
            }
            $ksRes = httpRequest($moUrl, $ksData, $moHeaders, 'GET', 'params');
            $returnKsData = json_decode($ksRes, true);
            if (!empty($returnKsData) && !empty($returnKsData['info']['ok']) && $returnKsData['info']['ok'] == true) {
                $ksList = $returnKsData['data']['list'];
            } else {
                $this->log('ud-account-stat', '快手请求接口错误！' . $ksRes);
            }
            $bdRes = httpRequest($moUrl, $bdData, $bdHeaders, 'GET', 'params');
            $returnBdData = json_decode($bdRes, true);
            if (!empty($returnBdData) && !empty($returnBdData['info']['ok']) && $returnBdData['info']['ok'] == true) {
                $bdList = $returnBdData['data']['list'];
            } else {
                $this->log('ud-account-stat', 'bd请求接口错误！' . $ksRes);
            }
            $anaRes = httpRequest($moUrl, $bdData, $anaHeaders, 'GET', 'params');
            $returnAnaData = json_decode($anaRes, true);
            if (!empty($returnAnaData) && !empty($returnAnaData['info']['ok']) && $returnAnaData['info']['ok'] == true) {
                $anaList = $returnAnaData['data']['list'];
            } else {
                $this->log('ud-account-stat', 'ana请求接口错误！' . $ksRes);
            }
            return array_merge($ttList, $gdtList, $ksList, $bdList, $anaList);

        } catch (\Exception $e) {
            $this->log('ud-account-stat', 'ud获取报表数据失败' . $e->getFile() . ',' . $e->getLine() . ',' . $e->getMessage());
        }

    }

    private function getXsrfToken($cookie)
    {
        $cookieArr = explode(';', $cookie);
        foreach ($cookieArr as $val) {
            $temArr = explode('=', $val);
            if (trim($temArr[0]) == 'XSRF-TOKEN') {
                return $temArr[1];
            }
        }
    }

}
