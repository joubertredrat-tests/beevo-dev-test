<?php

namespace Tests\Unit\Session;

use App\Exception\Session\SessionHandler\UnknownAdapterException;
use App\Session\Adapter\Redis;
use App\Session\SessionHandler;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Session\Adapter\RedisTest;

/**
 * SessionHandlerTest
 *
 * @package Tests\Unit\Session
 */
class SessionHandlerTest extends TestCase
{

    /**
     * @return void
     * @throws UnknownAdapterException
     */
    public function testRegisterRedis(): void
    {
        $adapterType = Redis::getName();
        $config = RedisTest::getValidConfig();

        $response = SessionHandler::register($adapterType, $config);

        self::assertNull($response);
    }

    /**
     * @return void
     * @throws UnknownAdapterException
     */
    public function testRegisterUnknownAdapterException(): void
    {
        self::expectException(UnknownAdapterException::class);

        $adapterType = 'mysql';
        $config = [
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => 'password',
            'database' => 'session',
        ];

        SessionHandler::register($adapterType, $config);
    }
}
