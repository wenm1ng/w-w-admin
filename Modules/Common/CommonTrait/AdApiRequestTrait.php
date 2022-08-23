<?php

namespace Modules\Common\CommonTrait;

use Modules\Admin\Models\OncesAdvertiser;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;

trait AdApiRequestTrait
{
    /**
     * 头条常规API处理
     * @param array $params
     * apiData 请求参数
     * apiUrl 请求地址
     * headers 头部信息
     * methon 请求方式
     * logname 日志名称
     */
    public function curlPackage(array $params)
    {
        try {
            $apiData = $params['apiData'];
            $url = $params['apiUrl'];
            $method = $params['method'] ?? 'POST';
            $headers = $params['headers'];
            $headers[] = 'Content-Type: application/json';
            $jsonRes = httpRequest($url, $apiData, $headers, $method);
            $res = json_decode($jsonRes, true);
            if (!empty($res) && isset($res['code']) && $res['code'] == 0) {
                return ['status' => StatusData::Ok, 'data' => $res];
            } else {
                $this->log($params['logname'] ?? 'onces', $res['message'], ['url' => $url, 'apidata' => $apiData, 'returndata' => $res]);
                return ['status' => StatusData::BAD_REQUEST, 'message' => $res['message'] ?? MessageData::SYSTEM_ERROR];
            }
        } catch (\Exception $e) {
            $this->log($params['logname'] ?? 'onces', $e->getMessage(), ['url' => $url, 'apidata' => $apiData, 'returndata' => ['local' => $e->getFile() . ',' . $e->getLine() . ',' . $e->getMessage(), 'res' => $res ?? '']]);
            throw new ApiException(['status' => StatusData::BAD_REQUEST, 'message' => $e->getMessage()]);
        }
    }


    public function adRequest($url, $apiData, $data, $method = 'POST')
    {
        try {

            $advertiser = OncesAdvertiser::where('advertiser_id', $apiData['advertiser_id'])->where('state', 1)->first();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Access-Token: ' . $advertiser->access_token;

            $jsonRes = httpRequest($url, $apiData, $data['headers'], $method);
            $res = json_decode($jsonRes, true);

            if (!empty($res) && isset($res['code']) && $res['code'] == 0) {
                return ['status' => StatusData::Ok, 'data' => $res];
            } else {
                $this->log($params['logname'] ?? 'onces', $res['message'], ['url' => $url, 'apidata' => $apiData, 'returndata' => $res]);
                return ['status' => StatusData::BAD_REQUEST, 'message' => $res['message'] ?? MessageData::SYSTEM_ERROR];
            }
        } catch(\Exception $e) {
            $this->log($params['logname'] ?? 'onces', $e->getMessage(), ['url' => $url, 'apidata' => $apiData, 'returndata' => ['local' => $e->getFile() . ',' . $e->getLine() . ',' . $e->getMessage(), 'res' => $res ?? '']]);
            throw new ApiException(['status' => StatusData::BAD_REQUEST, 'message' => $e->getMessage()]);
        }

        //统一处理

    }
}


