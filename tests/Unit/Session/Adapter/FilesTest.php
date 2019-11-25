<?php

declare(strict_types=1);

namespace Tests\Unit\Session\Adapter;

use App\Exception\Session\Adapter\Files\InvalidConfigException;
use App\Session\Adapter\Files;
use PHPUnit\Framework\TestCase;
use function file_exists;
use function file_get_contents;
use function serialize;
use function sprintf;
use function uniqid;

/**
 * FilesTest
 *
 * @package Tests\Unit\Session\Adapter
 */
class FilesTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstructWithInvalidConfigException(): void
    {
        self::expectException(InvalidConfigException::class);

        new Files([
            'paths' => __DIR__,
        ]);
    }

    /**
     * @return void
     */
    public function testClose(): void
    {
        $config = [
            'path' => __DIR__,
        ];

        $files = new Files($config);

        self::assertTrue(
            $files->close()
        );
    }

    /**
     * @return void
     */
    public function testDestroy(): void
    {
        $sessionId = uniqid();
        $path = __DIR__;
        $config = [
            'path' => $path,
        ];

        $files = new Files($config);
        $files->write(
            $sessionId,
            serialize([
                'foo' => 'bar',
                'id' => 10,
                'is' => true,
            ])
        );

        $testFileExists = file_exists(
            sprintf('%s/sess_%s', $path, $sessionId)
        );

        $files->destroy($sessionId);

        $testFileNotExists = file_exists(
            sprintf('%s/sess_%s', $path, $sessionId)
        );

        self::assertTrue($testFileExists);
        self::assertFalse($testFileNotExists);
    }

    /**
     * @return void
     */
    public function testGc(): void
    {
        $config = [
            'path' => __DIR__,
        ];

        $files = new Files($config);

        self::assertTrue(
            $files->gc(10)
        );
    }

    /**
     * @return void
     */
    public function testOpen(): void
    {
        $config = [
            'path' => __DIR__,
        ];

        $files = new Files($config);

        self::assertTrue(
            $files->open('save_path', 'name')
        );
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        $sessionId = uniqid();
        $path = __DIR__;
        $config = [
            'path' => $path,
        ];

        $files = new Files($config);
        $response = $files->write(
            $sessionId,
            serialize([
                'foo' => 'bar',
                'id' => 10,
                'is' => true,
            ])
        );

        $sessionData = file_get_contents(
            sprintf('%s/sess_%s', $path, $sessionId)
        );

        $sessionDataToTest = $files->read($sessionId);

        $files->destroy($sessionId);

        self::assertTrue($response);
        self::assertEquals($sessionData, $sessionDataToTest);
    }

    /**
     * @return void
     */
    public function testReadWithInvalidSessionId(): void
    {
        $sessionId = uniqid();
        $path = __DIR__;
        $config = [
            'path' => $path,
        ];

        $files = new Files($config);
        $sessionDataToTest = $files->read($sessionId);

        $files->destroy($sessionId);

        self::assertEmpty($sessionDataToTest);
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        $sessionId = uniqid();
        $path = __DIR__;
        $config = [
            'path' => $path,
        ];

        $sessionData = serialize([
            'foo' => 'bar',
            'id' => 10,
            'is' => true,
        ]);

        $files = new Files($config);
        $response = $files->write($sessionId, $sessionData);

        $sessionDataToTest = file_get_contents(
            sprintf('%s/sess_%s', $path, $sessionId)
        );

        $files->destroy($sessionId);

        self::assertTrue($response);
        self::assertEquals($sessionData, $sessionDataToTest);
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals('files', Files::getName());
    }

    /**
     * @return void
     */
    public function testIsValidConfig(): void
    {
        $config = [
            'path' => __DIR__,
        ];

        self::assertTrue(Files::isValidConfig($config));
    }

    /**
     * @return void
     */
    public function testIsNotValidConfig(): void
    {
        $config = [
            'random' => __DIR__,
        ];

        self::assertFalse(Files::isValidConfig($config));
    }
}
