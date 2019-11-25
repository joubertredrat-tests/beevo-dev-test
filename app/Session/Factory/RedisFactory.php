<?php

declare(strict_types=1);

namespace App\Session\Factory;

use App\Exception\Session\Adapter\Redis\InvalidConfigException;
use App\Session\Adapter\Redis;
use Predis\Client;

/**
 * RedisFactory
 *
 * @package App\Session\Factory
 */
class RedisFactory
{
    /**
     * @param array $config
     * @return Redis
     */
    public static function createFromSessionHandler(array $config): Redis
    {
        if (!Redis::isValidConfig($config)) {
            throw InvalidConfigException::throwNew();
        }

        $redisClient = new Client([
            'scheme' => 'tcp',
            'host' => $config['host'],
            'port' => $config['port'],
        ]);

        return new Redis($redisClient, $config);
    }
}
