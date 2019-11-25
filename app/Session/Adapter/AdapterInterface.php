<?php

declare(strict_types=1);

namespace App\Session\Adapter;

use SessionHandlerInterface;

/**
 * AdapterInterface
 *
 * @package App\Session\Adapters
 */
interface AdapterInterface extends SessionHandlerInterface
{
    /**
     * @return array
     */
    public function getConfig(): array;

    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return array
     */
    public static function getConfigSchema(): array;

    /**
     * @param array $config
     * @return bool
     */
    public static function isValidConfig(array $config): bool;
}
