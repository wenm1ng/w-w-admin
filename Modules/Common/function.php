<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-08 10:38
 */
//namespace Modules\Common;
//
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
/**
 * @desc       curl请求
 * @author     文明<736038880@qq.com>
 * @date       2022-08-08 10:52
 * @param        $url
 * @param string $method
 * @param null   $postfields
 * @param array  $headers
 * @param bool   $debug
 * @param string $postFieldsType
 * @param array  $ProxyArr
 *
 * @return bool|string
 */
function httpRequest($url, $postfields = null, $headers = [], $method = 'POST', $postFieldsType = "json", $ProxyArr = [])
{
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case 'GET':
            //拼接参数
            if(!empty($postfields)){
                if ($postFieldsType == "json") {
                    $postfields = json_encode($postfields);
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);

                }else{
                    $url .= '?'.http_build_query($postfields);
                    if(strpos($url, '%5B0%5D') !== false){
                        $url = preg_replace("/\%5B\d+\%5D/",'',$url);
                    }
                }
            }
            break;
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
    $response = json_encode(json_decode($response, true), JSON_UNESCAPED_UNICODE);
    return $response;
}


function postFile($url, $postfields = null, $headers = [], $method = 'POST', $extData = [])
{

    if(isset($extData['file_filed'])){
        if(isset($postfields[$extData['file_filed']])){
            $postfields[$extData['file_filed']] = new CURLFILE($postfields[$extData['file_filed']]);
        }
    }
    $ch = curl_init();
    curl_setopt($ch , CURLOPT_URL , $url);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch , CURLOPT_POST, 1);
    curl_setopt($ch , CURLOPT_FOLLOWLOCATION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch , CURLOPT_HTTP_VERSION, true);
    curl_setopt($ch , CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch , CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_TIMEOUT, 11);  //头条的默认最长10s
    curl_setopt($ch , CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    $ssl = preg_match('/^https:\/\//i', $url) ? true : false;
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
    }

    $resour = curl_exec($ch);
    curl_close($ch);
//    $requestinfo = curl_getinfo($ch);
    return $resour;
}

/**
 * @desc       返回随机字符串
 * @author     文明<736038880@qq.com>
 * @date       2022-08-20 13:59
 * @param      $len
 * @param bool $special
 *
 * @return string
 */
function getRandomStr($len, $special=true){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    if($special){
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);                            //打乱数组顺序
    $str = '';
    for($i=0; $i<$len; $i++){
        $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
    }
    return $str;
}

/**
 * @desc       分开文件记录日志,帮助函数形式
 * @author     文明<736038880@qq.com>
 * @date       2022-08-23 10:17
 * @param string $fileName
 * @param string $message
 * @param array  $data
 */
function adminLog(string $fileName, string $message, array $data = []){
    (new Logger('local'))
        ->pushHandler(new RotatingFileHandler(storage_path('logs/'.date('Y-m-d').'/'.$fileName.'.log')))
        ->info($message, $data);
}

/**
 * @desc       以某个键值进行分组
 * @author     文明<736038880@qq.com>
 * @date       2022-08-25 9:34
 * @param array  $arr
 * @param        $groupKey
 * @param string $beValKey
 *
 * @return array
 */
function arrayGroup(array $arr, $groupKey, $beValKey = '')
{
    $return = [];
    if ($beValKey) {
        foreach ($arr as $key => $val) {
            $return[$val[$groupKey]][] = $val[$beValKey];
        }
    } else {
        foreach ($arr as $key => $val) {
            $return[$val[$groupKey]][] = $val;
        }
    }
    return $return;
}

function getWowWeekYear(string $dateTime){
    $time = strtotime($dateTime);
    $year = date('Y', $time);
    $month = (int)date('m', $time);
    $week = (int)date('W', $time);
    if($week > 40 && $month == 1){
        //第二年的头几天，当成前一年最后一周算
        $year = $year - 1;
    }
    $yearLinkWeek = [
        2021 => 1,
        2022 => 1,
        2023 => 1,
        2024 => 1,
        2025 => 1,
        2026 => 0,
    ];
    $week = $week + $yearLinkWeek[$year];

    return ['year' => $year, 'week' => $week];
}
