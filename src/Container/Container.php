<?php
namespace Majh\ServerContainer\Container;

interface Container {

    function connect(array $config);

    function register(ServerInfo $serverInfo);
    function ping(ServerInfo $serverInfo);

    function servers(string $serverName = '',string $serverId = '');

}