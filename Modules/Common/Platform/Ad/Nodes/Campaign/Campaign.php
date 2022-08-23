<?php
/*
 * @desc       广告组api
 * @author     文明<736038880@qq.com>
 * @date       2022-08-17 16:18
 */
namespace Modules\Common\Platform\Ad\Nodes\Campaign;

use Modules\Common\Platform\Ad\Nodes\NodeAbstract;

Class Campaign extends NodeAbstract{
    /*
     * 获取广告组
     */
    public function getCampaign($parameters = []){
        return $this->get('/open_api/2/campaign/get', $parameters);
    }
    /*
     * 创建广告组
     */
    public function createCampaign($parameters = []){
        return $this->post('/open_api/2/campaign/create', $parameters);
    }

}
