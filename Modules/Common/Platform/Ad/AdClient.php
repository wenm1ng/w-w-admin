<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-17 17:21
 */
namespace Modules\Common\Platform\Ad;

use Modules\Common\Exceptions\ApiException;
use Modules\Admin\Models\Platform\AuthThird;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\OncesAdvertiser;

class AdClient extends BaseApiService{

    protected $advertiserId;
    protected $accountId;
    protected $client;
    protected $appId;
    protected $appSecret;
    protected $accountData = [];

    protected $advanceTime = 3600;

    public function __construct($config)
    {
        parent::__construct();
        $this->advertiserId = $config['advertiser_id'] ?? 0;
        $this->accountId = $config['account_id'] ?? 0;
        $this->appId = env('AD_APP_ID');
        $this->appSecret = env('AD_APP_SECRET');
//        $this->getClient();
    }

    public function getClient(){
        if(!empty($this->client)){
            return $this->client;
        }
        if(empty($this->advertiserId) && empty($this->accountId)){
            $this->apiError('账户id和子户id必填一项');
        }
        $this->getAccount();
        $this->client = new Client($this->accountData);
        return $this->client;
    }

    protected function getAccount(){
        if(!empty($this->advertiserId)){
            $info = OncesAdvertiser::query()
                ->where('advertiser_id', $this->advertiserId)
                ->with([
                    'third_accounts' => function ($query) {
                        $query->select('access_token', 'refresh_token', 'access_token_time', 'json_extend', 'account_id');
                    },
                ])
                ->first();
            if(empty($info)){
                $this->apiError('子账户不存在');
            }
            $info = $info->toArray();
            $info = array_merge($info, $info['third_accounts']);
        } else if(!empty($this->accountId)){
            $info = AuthThird::query()->where('account_id', $this->accountId)->first();
            if(empty($info)){
                $this->apiError('账号不存在');
            }
            $info = $info->toArray();
        }
        $this->accountData = $info;

        if(strtotime($info['access_token_time']) - $this->advanceTime <= time()){
            //进行token刷新操作
            $this->authFreshToken($info);
        }
    }

    /**
     * @desc       刷新token
     * @author     文明<736038880@qq.com>
     * @date       2022-08-09 14:54
     * @param string $accountId
     */
    public function authFreshToken(array $accountInfo){
        $url = 'https://ad.oceanengine.com/open_api/oauth2/refresh_token/';
        $data = [
            'app_id' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $accountInfo['refresh_token']
        ];
        $headers = [
            'Accept:'. '*/*',
            'Content-Type: application/json'
        ];
        $result = httpRequest($url, $data, $headers);
        $this->log('freshToken_Response', $result, $data);

        $result = json_decode($result, true);
        $jsonExtend = json_decode($accountInfo['json_extend'], true);
        $jsonExtend = is_array($jsonExtend) ? $jsonExtend : [];
        $jsonExtend = array_merge($jsonExtend, $result['data']);
        $dbData = [
            'access_token' => $result['data']['access_token'],
            'refresh_token' => $result['data']['refresh_token'],
            'json_extend' => json_encode($jsonExtend, JSON_UNESCAPED_UNICODE),
            'access_token_time' => date('Y-m-d H:i:s', time() + $result['data']['expires_in']),
            'refresh_token_time' => date('Y-m-d H:i:s', time() + $result['data']['refresh_token_expires_in']),
        ];
        AuthThird::query()->where('account_id', $accountInfo['account_id'])->update($dbData);
        $this->accountData = array_merge($this->accountData, $dbData);
    }
}
