<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-18 10:17
 */
namespace Modules\Common\Platform\Ad\Nodes\Plan;

use Modules\Common\Platform\Ad\Nodes\NodeAbstract;

Class Plan extends NodeAbstract{
    /*
     * 获取广告计划
     */
    public function getPlan($parameters = []){
        return $this->get('/open_api/2/ad/get', $parameters);
    }
    /*
     * 创建广告计划
     */
    public function addPlan($parameters = []){
        return $this->post('/open_api/2/ad/create', $parameters);
    }

}
