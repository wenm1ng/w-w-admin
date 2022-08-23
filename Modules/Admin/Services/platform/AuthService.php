<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-08 9:51
 */
namespace Modules\Admin\Services\platform;


use Modules\Admin\Models\Platform\Platform;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Platform\AuthThird;

class AuthService extends BaseApiService
{
    /**
     * @var mixed|string 应用id
     */
    protected $appId = '';
    /**
     * @var mixed|string 秘钥
     */
    protected $secret = '';
    /**
     * @var int 刷新token时间提前量,单位：秒
     */
    protected $advanceTime = 3600;

    public function __construct()
    {
        $this->appId = env('AD_APP_ID');
        $this->secret = env('AD_APP_SECRET');
    }

    /**
     * @desc       获取初始授权链接
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:26
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUrl(){
        $url = "https://ad.oceanengine.com/openapi/audit/oauth.html?app_id={$this->appId}&redirect_uri=http://admanage.megacombine.com:9170/api/v1/admin/back";

        return $this->apiSuccess('', [
            'url' => $url
        ]);
    }


    /**
     * @desc       授权成功回调地址
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:26
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function back(array $params){
        $this->log('back_params', json_encode($params, JSON_UNESCAPED_UNICODE));

        $result = $this->authAccessToken($params['auth_code']);
        if($result['code'] !== 0){
            $this->apiError($result['message'] ?? '授权失败');
        }
        $accountId = AuthThird::query()->where('account_id', $result['data']['advertiser_id'])->value('account_id');
        $dbData = [
            'platform_id' => 1,
            'account_id' => $result['data']['advertiser_id'],
            'account_name' => $result['data']['advertiser_name'],
            'access_token' => $result['data']['access_token'],
            'refresh_token' => $result['data']['refresh_token'],
            'json_extend' => json_encode($result['data'], JSON_UNESCAPED_UNICODE),
            'access_token_time' => date('Y-m-d H:i:s', time() + $result['data']['expires_in']),
            'refresh_token_time' => date('Y-m-d H:i:s', time() + $result['data']['refresh_token_expires_in']),
        ];
        if(empty($accountId)){
            //新增
            AuthThird::query()->insert($dbData);
        }else{
            //编辑
            AuthThird::query()->where('account_id', $accountId)->update($dbData);
        }
        return $this->apiSuccess();
    }

    /**
     * @desc       授权accessToken
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:28
     * @param string $authCode
     *
     * @return bool|string
     */
    public function authAccessToken(string $authCode){
        $url = 'https://ad.oceanengine.com/open_api/oauth2/access_token/';
        $data = [
            'app_id' => $this->appId,
            'secret' => $this->secret,
            'grant_type' => 'auth_code',
            'auth_code' => $authCode
        ];
        $headers = [
            'Accept:'. '*/*',
            'Content-Type: application/json'
        ];
        $result = httpRequest($url, $data, $headers);
        $this->log('accessToken_Response', $result, $data);

        $result = json_decode($result, true);
        return $result;
    }

    /**
     * @desc       获取授权列表
     * @author     文明<736038880@qq.com>
     * @date       2022-08-08 13:57
     * @param array $params
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthList(array $params){
        $where = [
            'order' => ['updated_at' => 'desc', 'id' => 'desc'],
            'where' => [
                ['platform_id', '=', $params['platform_id']]
            ],
            'with' => ['platform' => 'id,platform_name']
        ];
        $list = AuthThird::getPageList($where);
        return $this->apiSuccess('', $list);
    }

    /**
     * @desc       刷新token
     * @author     文明<736038880@qq.com>
     * @date       2022-08-09 14:54
     * @param string $accountId
     */
    public function authFreshToken(string $accountId){
        $info = AuthThird::query()->where('account_id', $accountId)->first();
        if(empty($info)){
            $this->apiError('账号不存在');
        }

        $info = $info->toArray();
        if(empty($info['access_token_time'])){
            $this->apiError('token有误，请重新授权');
        }
        if(strtotime($info['access_token_time']) - $this->advanceTime > time()){
            //不需要进行刷新token操作
            return;
        }

        $url = 'https://ad.oceanengine.com/open_api/oauth2/refresh_token/';
        $data = [
            'app_id' => $this->appId,
            'secret' => $this->secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $info['refresh_token']
        ];
        $headers = [
            'Accept:'. '*/*',
            'Content-Type: application/json'
        ];
        $result = httpRequest($url, $data, $headers);
        $this->log('freshToken_Response', $result, $data);

        $result = json_decode($result, true);
        $jsonExtend = json_decode($info['json_extend'], true);
        $jsonExtend = is_array($jsonExtend) ? $jsonExtend : [];
        $jsonExtend = array_merge($jsonExtend, $result['data']);
        $dbData = [
            'access_token' => $result['data']['access_token'],
            'refresh_token' => $result['data']['refresh_token'],
            'json_extend' => json_encode($jsonExtend, JSON_UNESCAPED_UNICODE),
            'access_token_time' => date('Y-m-d H:i:s', time() + $result['data']['expires_in']),
            'refresh_token_time' => date('Y-m-d H:i:s', time() + $result['data']['refresh_token_expires_in']),
        ];
        AuthThird::query()->where('account_id', $accountId)->update($dbData);
    }
}
