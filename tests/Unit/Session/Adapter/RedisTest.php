<?php

declare(strict_types=1);

namespace Tests\Unit\Session\Adapter;

use App\Exception\Session\Adapter\Redis\InvalidConfigException;
use App\Session\Adapter\Redis;
use Mockery;
use PHPUnit\Framework\TestCase;
use Predis\ClientInterface;

/**
 * RedisTest
 *
 * @package Tests\Unit\Session\Adapter
 */
class RedisTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstructWithInvalidConfigException(): void
    {
        self::expectException(InvalidConfigException::class);

        $redisClientMock = Mockery::mock(ClientInterface::class);
        $config = [
            'wrong' => 'config',
        ];

        new Redis($redisClientMock, $config);
    }

    /**
     * @return void
     */
    public function testClose(): void
    {
        $redisClientMock = Mockery::mock(ClientInterface::class);
        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        self::assertTrue(
            $redis->close()
        );
    }

    /**
     * @return void
     */
    public function testDestroy(): void
    {
        $sessionId = uniqid();
        $redisClientMock = Mockery::spy(ClientInterface::class);
        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        $response = $redis->destroy($sessionId);

        $redisClientMock
            ->shouldHaveReceived(
                'del',
                [
                    [$sessionId],
                ]
            )
            ->once()
        ;

        self::assertTrue($response);
    }

    /**
     * @return void
     */
    public function testGc(): void
    {
        $redisClientMock = Mockery::mock(ClientInterface::class);
        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        self::assertTrue(
            $redis->gc(10)
        );
    }

    /**
     * @return void
     */
    public function testOpen(): void
    {
        $redisClientMock = Mockery::mock(ClientInterface::class);
        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        self::assertTrue(
            $redis->open('save_path', 'name')
        );
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        $sessionId = uniqid();
        $sessionData = serialize([
            'foo' => 'bar',
            'id' => 10,
            'is' => true,
        ]);

        $redisClientMock = Mockery::spy(ClientInterface::class);
        $redisClientMock
            ->shouldReceive('get')
            ->with($sessionId)
            ->andReturn($sessionData)
        ;

        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        $response = $redis->read($sessionId);

        $redisClientMock
            ->shouldHaveReceived(
                'get',
                [
                    $sessionId,
                ]
            )
            ->once()
        ;

        self::assertEquals($sessionData, $response);
    }

    /**
     * @return void
     */
    public function testReadWithEmptyData(): void
    {
        $sessionId = uniqid();

        $redisClientMock = Mockery::spy(ClientInterface::class);
        $redisClientMock
            ->shouldReceive('get')
            ->with($sessionId)
            ->andReturn(null)
        ;

        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        $response = $redis->read($sessionId);

        $redisClientMock
            ->shouldHaveReceived(
                'get',
                [
                    $sessionId,
                ]
            )
            ->once()
        ;

        self::assertEmpty($response);
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        $sessionId = uniqid();
        $sessionData = serialize([
            'foo' => 'bar',
            'id' => 10,
            'is' => true,
        ]);

        $redisClientMock = Mockery::spy(ClientInterface::class);
        $config = self::getValidConfig();

        $redis = new Redis($redisClientMock, $config);

        $response = $redis->write($sessionId, $sessionData);

        $redisClientMock
            ->shouldHaveReceived(
                'set',
                [
                    $sessionId,
                    $sessionData,
                ]
            )
            ->once()
        ;

        self::assertTrue($response);
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals('redis', Redis::getName());
    }

    /**
     * @return void
     */
    public function testIsValidConfig(): void
    {
        $config = self::getValidConfig();

        self::assertTrue(Redis::isValidConfig($config));
    }

    /**
     * @return void
     */
    public function testIsNotValidConfig(): void
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'other' => 'config',
        ];

        self::assertFalse(Redis::isValidConfig($config));
    }

    /**
     * @return array
     */
    public static function getValidConfig(): array
    {
        return [
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
    }
}
