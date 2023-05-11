<?php

include 'vendor/autoload.php';

use \Majh\ServerContainer\Container\RedisContainer as Container;
use \Majh\ServerContainer\Container\ServerInfo as ServerInfo;


$config = [
    'host' => '127.0.0.1',
    'port' => 6379,
//    'password' => ''
];
$container = new Container($config);

$serverInfo = new ServerInfo();
$serverInfo->serverName = 'MyServer';

$container->register($serverInfo);

while (true){
    sleep(5);
}