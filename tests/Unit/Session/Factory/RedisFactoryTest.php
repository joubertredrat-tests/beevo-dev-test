<?php

declare(strict_types=1);

namespace Tests\Unit\Session\Factory;

use App\Exception\Session\Adapter\Redis\InvalidConfigException;
use App\Session\Adapter\Redis;
use App\Session\Factory\RedisFactory;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Session\Adapter\RedisTest;

/**
 * RedisFactoryTest
 *
 * @package Tests\Unit\Session\Factory
 */
class RedisFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateFromSessionHandler(): void
    {
        $config = RedisTest::getValidConfig();
        $redis = RedisFactory::createFromSessionHandler($config);

        self::assertInstanceOf(Redis::class, $redis);
    }

    /**
     * @return void
     */
    public function testCreateFromSessionHandlerWithInvalidConfig(): void
    {
        self::expectException(InvalidConfigException::class);

        $config = [
            'foo' => 'bar',
        ];

        RedisFactory::createFromSessionHandler($config);
    }
}
