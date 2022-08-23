<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-17 16:17
 */
namespace Modules\Common\Platform\Ad;

use Modules\Common\Platform\Ad\Nodes;
use Modules\Common\Platform\Ad\Nodes\NodeAbstract;
use Modules\Common\Exceptions\ApiException;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\CommonTrait\AdApiRequestTrait;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;

/**
 * @property Nodes\Campaign\Campaign campaign
 * @property Nodes\Plan\Plan plan
 * @property Nodes\Assets\Assets assets
 */
class Client
{
    use AdApiRequestTrait;
    protected $appId;
    protected $appSecret;
    protected $access_token;
    protected $refresh_token;

    /** @var NodeAbstract[] */
    protected $nodes = [];
    protected $baseUrl = 'https://ad.oceanengine.com';

    protected $logName = 'ad';
    public function __construct(array $config = [])
    {
        $this->appId = env('AD_APP_ID');
        $this->appSecret = env('AD_APP_SECRET');
        $this->access_token = $config['access_token'];
        $this->refresh_token = $config['refresh_token'];
        $this->nodes['campaign'] = new Nodes\Campaign\Campaign($this);
        $this->nodes['plan'] = new Nodes\Plan\Plan($this);
        $this->nodes['assets'] = new Nodes\Assets\Assets($this);
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->nodes)) {
            throw new ApiException([
                'status' => 40000,
                'message' => $name.'属性不存在'
            ]);
        }

        return $this->nodes[$name];
    }

    /**
     * @desc       curl请求
     * @author     文明<736038880@qq.com>
     * @date       2022-08-18 14:11
     * @param          $url
     * @param null     $postfields
     * @param string   $method
     * @param array    $fileFields
     * @param string[] $headers
     * @param string   $postFieldsType
     * @param array    $ProxyArr
     *
     * @return array
     */
    public function request($url, $postfields = null, $method = 'POST', $fileFields = [], $headers = ['Accept:'. '*/*','Content-Type: application/json'], $postFieldsType = "json", $ProxyArr = [])
    {
        $return = ['status' => StatusData::BAD_REQUEST,'message' => '', 'data' => []];
        try{
            $url = $this->baseUrl. $url;
            $method = strtoupper($method);
            $ci = curl_init();
            /* Curl settings */
            curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
            curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);



            if(!empty($fileFields)){
                foreach ($fileFields as $field) {
                    if(isset($postfields[$field])){
                        $postfields[$field] = new \CURLFILE($postfields[$field]);
                    }
                }
            }

            switch ($method) {
                case "POST":
                    curl_setopt($ci, CURLOPT_POST, true);
                    if (null !== $postfields) {
                        if ($postFieldsType == "json") {
                            $postfields = json_encode($postfields);
                            curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);

                        } else {
                            $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                            curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                        }
                    }
                    break;
                case 'PUT':
                    curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                    if (null !== $postfields) {
                        if ($postFieldsType == "json") {
                            $postfields = json_encode($postfields);
                            curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                        } else {
                            $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                            curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                        }
                    }
                    break;
                case 'GET':
                    //拼接参数
                    if(!empty($postfields)){
                        $url .= '?'.http_build_query($postfields);
                        if(strpos($url, '%5B0%5D') !== false){
                            $url = preg_replace("/\%5B\d+\%5D/",'',$url);
                        }
                    }
                    break;
                default:
                    curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                    if ($postFieldsType == "json") {
                        $postfields = json_encode($postfields);
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);

                    }
                    break;
            }
            $ssl = preg_match('/^https:\/\//i', $url) ? true : false;
            curl_setopt($ci, CURLOPT_URL, $url);
            if ($ssl) {
                curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
                curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
            }
            //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
            curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/

            $headers[] = 'Access-Token:'.$this->access_token;
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);


            curl_setopt($ci, CURLINFO_HEADER_OUT, true);
            /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */


            if (!empty($ProxyArr)) {
                curl_setopt($ci, CURLOPT_PROXY, $ProxyArr['ip']);
                curl_setopt($ci, CURLOPT_PROXYPORT, $ProxyArr['port']);
            }

            $response = curl_exec($ci);
            $requestinfo = curl_getinfo($ci);
            curl_close($ci);
            $responseArr = json_decode($response, true);
            $response = json_encode($responseArr, JSON_UNESCAPED_UNICODE);
            $this->log($response, is_array($postfields) ? $postfields : json_decode($postfields, true));

            if (isset($responseArr['code']) && $responseArr['code'] === 0) {
                $return['data'] = $responseArr['data'];
                $return['status'] = StatusData::Ok;
            } else {
                $return['message'] = $responseArr['message'] ?? MessageData::SYSTEM_ERROR;
            }
        }catch (\Throwable $e){
            $return['message'] = $e->getMessage();
        }

        return $return;
    }


//    public function fileRequest($url, $postfields = null, $headers = [], $method = 'POST', $extData = [])
//    {
//        $url = $this->baseUrl. $url;
//        if(isset($extData['file_filed'])){
//            if(isset($postfields[$extData['file_filed']])){
//                $postfields[$extData['file_filed']] = new \CURLFILE($postfields[$extData['file_filed']]);
//            }
//        }
//        $ch = curl_init();
//        curl_setopt($ch , CURLOPT_URL , $url);
//        curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch , CURLOPT_POST, 1);
//        curl_setopt($ch , CURLOPT_FOLLOWLOCATION, CURL_HTTP_VERSION_1_1);
//        curl_setopt($ch , CURLOPT_HTTP_VERSION, true);
//        curl_setopt($ch , CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($ch , CURLOPT_POSTFIELDS, $postfields);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 11);  //头条的默认最长10s
//
//        $headers[] = 'Access-Token:'.$this->access_token;
//        curl_setopt($ch , CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//
//        $ssl = preg_match('/^https:\/\//i', $url) ? true : false;
//        curl_setopt($ch, CURLOPT_URL, $url);
//        if ($ssl) {
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
//        }
//
//        $resour = curl_exec($ch);
//        curl_close($ch);
//        $this->log($resour, $postfields);
////    $requestinfo = curl_getinfo($ch);
//        return $resour;
//    }

    /**
     * @desc       分开文件记录日志
     * @author     文明<736038880@qq.com>
     * @date       2022-07-09 11:24
     * @param string $fileName
     * @param string $message
     * @param array  $data
     */
    public function log(string $message, $data = []){
        $data = !empty($data) ? $data : [];
        (new BaseApiService())->log($this->logName, $message, $data);
    }
}
