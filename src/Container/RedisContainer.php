<?php
namespace Majh\ServerContainer\Container;

class RedisContainer implements Container {

    /**
     *
    */
    private \Redis $conn;

    const SERVER_LIST_KEY = 'container:server:list';
    const SERVER_LIST_INFO = 'container:server:list:info:';
    const SERVER_ID_LIST = 'container:server:id:list:';

    public function __construct(array $config)
    {
        $this->connect($config);
    }

    function connect(array $config)
    {
        $redis = new \Redis();
        if (isset($config['persist']) && $config['persist']) {
            $redis->pconnect($config['host'], $config['port']);
        } else {
            $redis->connect($config['host'], $config['port']);
        }
        if (isset($config['password'])) {
            $redis->auth($config['password']);
        }
        if (isset($this->option['index'])) {
            $redis->select($this->option['index']);
        }
        if (isset($this->option['prefix'])) {
            $redis->setOption(\Redis::OPT_PREFIX, $this->option['prefix']);
        }
        $this->conn = $redis;
    }

    function register(ServerInfo $serverInfo)
    {
        // TODO: Implement register() method.
        if(trim($serverInfo->serverName) == ''){
            throw new \Exception('serverName could not be empty');
        }
        if(trim($serverInfo->serverId) == ''){
            $serverInfo->serverId = uniqid();
        }
        $this->conn->sAdd(self::SERVER_LIST_KEY,$serverInfo->serverName);
        $this->conn->zAdd(self::SERVER_ID_LIST.$serverInfo->serverName,time(),$serverInfo->serverName.'|'.$serverInfo->serverId);
        $info = [
            'remark' => $serverInfo->remark,
            'version' => $serverInfo->verson,
        ];
        $this->conn->hSet(self::SERVER_LIST_INFO.$serverInfo->serverName,$serverInfo->serverId,json_encode($info));
    }

    function servers(string $serverName = '', string $serverId = '')
    {
        // TODO: Implement servers() method.
    }

    function ping(ServerInfo $serverInfo){
        if($this->conn->zScore(self::SERVER_ID_LIST.$serverInfo->serverName,$serverInfo->serverName.'|'.$serverInfo->serverId) !== false){
            $this->conn->zAdd(self::SERVER_ID_LIST.$serverInfo->serverName,time(),$serverInfo->serverName.'|'.$serverInfo->serverId);
        }
    }

}