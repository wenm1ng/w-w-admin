<?php
/*
 * 报警规则设置
 * 判断有几个规则被开启了,几个规则被关闭了,
 */
function monitorDecrypt($data){
    $sum = 0;
    for($i=0;$i<count($data);$i++){
        if($data[$i] == 1){
            $sum += pow(2,$i);
        }
    }
    return $sum;
}

/*
 * 数据库取出,计算出规则开启序列
 * 多选解密
 */
function monitorEncrypt($str,&$data = [0,0,0,0,0]){
    //
    $n = (int)log($str,2);
    if($n){
        $data[$n] = 1;
        $m = $str - pow(2,$n);
        if($m){
            monitorEncrypt($m,$data);
        }
    }

      //print_r($data);
        return  $data;

}

