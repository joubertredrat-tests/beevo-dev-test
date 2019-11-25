<?php

declare(strict_types=1);

namespace App\Session\Factory;

use App\Session\Adapter\Files;

/**
 * FilesFactory
 *
 * @package App\Session\Factory
 */
class FilesFactory
{
    /**
     * @param array $config
     * @return Files
     */
    public static function createFromSessionHandler(array $config): Files
    {
        return new Files($config);
    }
}
