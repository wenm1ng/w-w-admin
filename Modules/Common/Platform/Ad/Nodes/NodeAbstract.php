<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-17 16:21
 */
namespace Modules\Common\Platform\Ad\Nodes;

use Modules\Common\Platform\Ad\Client;

abstract class NodeAbstract
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @desc       post请求
     * @author     文明<736038880@qq.com>
     * @date       2022-08-18 10:14
     * @param $uri
     * @param $parameters
     *
     * @return bool|string
     */
    public function post($uri, $parameters)
    {
        $response = $this->client->request($uri, $parameters, 'POST');
        return $response;
    }

    /**
     * @desc       get请求
     * @author     文明<736038880@qq.com>
     * @date       2022-08-18 10:14
     * @param       $uri
     * @param array $parameters
     *
     * @return bool|string
     */
    public function get($uri, $parameters = [])
    {
        $response = $this->client->request($uri, $parameters, 'GET');
        return $response;
    }

    /**
     * @desc       文件请求
     * @author     文明<736038880@qq.com>
     * @date       2022-08-18 10:58
     * @param $uri
     * @param $parameters
     * @param $fileFields
     *
     * @return bool|string
     */
    public function file($uri, $parameters, $fileFields)
    {
        $response = $this->client->request($uri, $parameters, 'POST', $fileFields);
        return $response;
    }
}
