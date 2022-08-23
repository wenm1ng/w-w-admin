<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-13 10:29
 */
namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Admin\Models\AuthAdmin;
use Modules\Common\Platform\Ad\AdClient;
use App\Console\Services\PlatformStatCrontab;

class TestController extends BaseApiController
{

    public function test(){
        //        $ipconfig =   shell_exec ("getmac");
//        $this->log('ipconfig', $ipconfig);

//        @exec("getmac", $a);
//        foreach ( $a as $value ) {
//
//            if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array)) {
//                $this->mac_addr = $temp_array[0];
//                $this->log('mac_address', $this->mac_addr);
//                break;
//            }
//        }
//        return response()->json([
//            'status' => 20000,
//            'message' => 'ok',
//            'data' => $this->mac_addr
//        ], 200);
//        AuthAdmin::query()->update();
//        $res = (new AdClient(['advertiser_id' => 1735053747698701]))->getClient()->campaign->getCampaign(['advertiser_id' => 1735053747698701]);
//        $client = (new AdClient(['advertiser_id' => 1735053747698701]))->getClient();
//        $res = $client->plan->getPlan(['advertiser_id' => 1735053747698701]);

//        $client = (new AdClient(['advertiser_id' => 1735053747698701]))->getClient();
//        $res = $client->assets->getAssets(['advertiser_id' => 1735053747698701, 'asset_type' => 'THIRD_EXTERNAL']);
//        print_r(json_encode($res, 256));
        adminLog('test','1111');
//        (new PlatformStatCrontab())->platformStatRun();
        echo 1;
    }
}
