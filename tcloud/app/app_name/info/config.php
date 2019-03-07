<?php

function config($index) {
    $config = array(
        'TRACE' => TRUE,
        'DEBUG' => FALSE,
        'PDO_OPTIONS' => array (
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        ),
        
        'GM_SERVER' => array (
            'ip' => "127.0.0.1",
            'port' => 10114,
        ),
        
        'ACCOUNT_REDIS' => array(
            'REDIS_IP' => '172.17.1.245',
            'REDIS_PORT' => 6379, //6379,
            'REDIS_AUTH' => '',
        ),
        'GAME_REDIS' => array(
            array(
                'REDIS_IP' => '172.17.1.245',
                'REDIS_PORT' => 6379, //6379,
                'REDIS_AUTH' => '',
            ),
        ),

        'ACCOUNT_DB' => array (
            'MYSQL_SERVER_NAME' => "172.17.1.245",
            'MYSQL_DSN' => "mysql:host=localhost;dbname=dragon",
            'MYSQL_USERNAME' => "root",
            'MYSQL_PASSWORD' => "root",
            'MYSQL_DATABASE' => "dragon",
        ),
        
        'GAME_DB' => array(
            array(
                'MYSQL_SERVER_NAME' => "172.17.1.245",
                'MYSQL_DSN' => "mysql:host=localhost;dbname=dragon",
                'MYSQL_USERNAME' => "root",
                'MYSQL_PASSWORD' => "root",
                'MYSQL_DATABASE' => "dragon",
            ),
        ),
        "LOGIC_SERVER_LIST"=>array(
        ),
        "PAY_IP_LIST"=>array(
        ),
        'NOTICE_URL' => "http://172.17.1.245/app/dragon/tpl/static/notice/",
        'ACTIVITY_URL' => "http://172.17.1.245/app/tank/tpl/static/activity_picture/",
    );
    
    return $config[$index];
}

