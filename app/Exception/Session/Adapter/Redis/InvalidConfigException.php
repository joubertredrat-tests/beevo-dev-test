<?php

namespace App\Exception\Session\Adapter\Redis;

use InvalidArgumentException;

/**
 * InvalidConfigException
 *
 * @package App\Exception\Session\Adapter\Redis
 */
class InvalidConfigException extends InvalidArgumentException
{
    /**
     * @return static
     */
    public static function throwNew(): self
    {
        return new self('Invalid configuration array');
    }
}
