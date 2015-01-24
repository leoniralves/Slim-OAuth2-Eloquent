<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 06/01/15
 * Time: 23:50
 */

$config['db'] = array(
    'driver'    => 'sqlite',
    'host'      => '127.0.0.1',
    'port'      => '',
    'database'  => __DIR__.'/../init/oauth2.sqlite3',
    'username'  => '',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
);

$config['app']['mode'] = $_ENV['SLIM_MODE'];

$config['app']['log.writer'] = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
    'handlers' => array(
        new \Monolog\Handler\StreamHandler(realpath(__DIR__ . '/../logs').'/'.$_ENV['SLIM_MODE'] . '_' .date('Y-m-d').'.log'),
    ),
));