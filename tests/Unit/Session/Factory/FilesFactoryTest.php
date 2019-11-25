<?php

declare(strict_types=1);

namespace Tests\Unit\Session\Factory;

use App\Session\Adapter\Files;
use App\Session\Factory\FilesFactory;
use PHPUnit\Framework\TestCase;

/**
 * FilesFactoryTest
 *
 * @package Tests\Unit\Session\Factory
 */
class FilesFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateFromSessionHandler(): void
    {
        $config = [
            'path' => __DIR__,
        ];

        $files = FilesFactory::createFromSessionHandler($config);

        self::assertInstanceOf(Files::class, $files);
    }
}
