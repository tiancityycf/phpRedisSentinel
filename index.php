<?php
index();
function index(){
    $config_name=array(
            'name'=>'210_6379_master',
            'list'=>['172.16.0.210:26379','172.16.0.211:26379','172.16.0.211:36379'],
            );
    getMaster($config_name);
}
/*
 * 功能： 获取 sentinel master info e.g: host port and so on...
 **************************************************************
 * @author:chiefyang
 * @date 2015-07-09
 * 参数：
 * $conf  array  配置信息 
 * return $result array e.g:['host'=>'127.0.0.1','port'=>'5379']
 */
function getMaster($conf) {
    include_once('./Sentinel.php');
    include_once('./sentinel/Client.php');
    include_once('./sentinel/ConnectionTcpExecption.php');
    include_once('./sentinel/ConnectionFailureExecption.php');

    if(empty($conf)){
        return false;    
    }
    $master_name    =   $conf['name'];
    $sentinel = new \Redis_Sentinel($master_name);
    foreach($conf['list'] as $v){
        $url    =   explode(":",$v);
        $sentinel->add(new \Redis_Sentinel_Client($url[0], $url[1]));
    }
    $master = $sentinel->getMaster();
    if(empty($master['ip']||empty($master['port']))){
        return false;
    }
    return $master;
    /*
       $master_name    =   '210_6379_master';
       $sentinel_client1 = new \Redis_Sentinel_Client("172.16.0.210", 26379);
       $sentinel_client2 = new \Redis_Sentinel_Client("172.16.0.211", 26379);
       $sentinel_client3 = new \Redis_Sentinel_Client("172.16.0.211", 36379);
       $sentinel->add($sentinel_client1);
       $sentinel->add($sentinel_client2);
       $sentinel->add($sentinel_client3);
     */
    /*
       print_r($sentinel_client1->ping());
       print_r($sentinel_client1->masters());
       print_r($sentinel_client2->ping());
       print_r($sentinel_client2->masters());
       print_r($sentinel_client1->slaves($master_name));
       print_r($sentinel_client2->slaves($master_name));
     */
    /*
       $i  =   0;
       while(1){
       $i++;
       echo "$i getMaster():","\r\n";
       $master = $sentinel->getMaster();
       print_r($master);
       echo "\r\n";

       $redis = new \Redis();
       $conn   =   $redis->connect($master["ip"], $master["port"],5);
       var_dump($conn);
       echo "set a 1";
       $redis->set("a", 1);
       echo "\r\n";

       echo "get a:",$redis->get("a");
       echo "\r\n";
       sleep(2);
       }
     */
}
?>
