<?php

require __DIR__ . '/../bootstrap.php';

use App\Session\Adapter\Files;
use App\Session\Adapter\Redis;
use App\Session\SessionHandler;

try {
    $configFiles = [
        'path' => dirname(__DIR__) . '/var/sessions',
    ];

    SessionHandler::register(Files::getName(), $configFiles);
    session_start();

    $_SESSION['foo-files'] = 'bar';

    $configRedis = [
        'host' => '127.0.0.1',
        'port' => 6379,
    ];

    session_destroy();
    SessionHandler::register(Redis::getName(), $configRedis);
    session_start();

    $_SESSION['foo-redis'] = 'bar';
} catch (\Throwable $e) {
    echo sprintf('Anything wrong is not right: [ %s ]', $e->getMessage());
}