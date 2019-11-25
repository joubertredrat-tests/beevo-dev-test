<?php

namespace App\Exception\Session\Adapter\Files;

use InvalidArgumentException;

/**
 * InvalidConfigException
 *
 * @package App\Exception\Session\Adapter\Files
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
