<?php

namespace App\Exception\Session\SessionHandler;

use Exception;
use function sprintf;

/**
 * Class UnknownAdapterException
 */
class UnknownAdapterException extends Exception
{
    /**
     * @param string $adapterType
     * @return static
     */
    public static function throwNew(string $adapterType): self
    {
        return new self(
            sprintf('Unknown session adapter type: [ %s ]', $adapterType)
        );
    }
}
