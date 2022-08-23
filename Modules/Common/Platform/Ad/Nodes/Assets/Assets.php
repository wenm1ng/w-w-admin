<?php
/*
 * @desc
 * @author     文明<736038880@qq.com>
 * @date       2022-08-19 13:27
 */
namespace Modules\Common\Platform\Ad\Nodes\Assets;

use Modules\Common\Platform\Ad\Nodes\NodeAbstract;

Class Assets extends NodeAbstract{
    /*
     * 获取资产
     */
    public function getAssets($parameters = []){
        return $this->get('/open_api/2/tools/event/assets/get', $parameters);
    }
}
