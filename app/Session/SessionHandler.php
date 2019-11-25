<?php

declare(strict_types=1);

/**
 * SessionHandler -> From a given adapter type, apply it to PHP the core.
 *
 * This service needs to interact to required adapter independently,
 * validating it existance and it sanity, simulating the connection.
 *
 * @example https://www.php.net/manual/en/function.session-set-save-handler.php
 *
 * <code>
 *  // use file
 *  SessionHandler::register('files');
 *  $_SESSION['foo'] = 'bar'; // should write on file
 *  echo $_SESSION['foo']; // should return 'bar' from written file
 *
 *  // register weirdo adapter
 *  SessionHandler::register('THIS_IS_A_DUMMY_ADAPTER'); // Exception thrown
 * </code>
 */


namespace App\Session;

use App\Session\Adapter\Files;
use App\Session\Adapter\Redis;
use App\Exception\Session\SessionHandler\UnknownAdapterException;
use App\Session\Factory\FilesFactory;
use App\Session\Factory\RedisFactory;
use function array_key_exists;
use function session_set_save_handler;

/**
 * Class SessionHandler
 */
class SessionHandler
{
    /**
     * @var string
     */
    protected $adapterType;

    /**
     * @var array
     */
    protected $adapterConfig;


    /**
     * SessionService constructor.
     *
     * @param string $adapterType Name of adapter (eg: redis)
     * @param array $adapterConfig
     */
    final public function __construct(string $adapterType, array $adapterConfig = [])
    {
        $this->adapterType = $adapterType;
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * @param string $adapterType
     * @param array $adapterConfig
     * @throws UnknownAdapterException
     */
    public static function register(string $adapterType, array $adapterConfig = []): void
    {
        $adapter = (new self($adapterType, $adapterConfig))
            ->getAdapter()
        ;

        session_set_save_handler($adapter, true);
    }

    /**
     * @return mixed
     * @throws UnknownAdapterException
     */
    protected function getAdapter()
    {
        if (!array_key_exists($this->adapterType, self::getAdaptersAvailable())) {
            throw UnknownAdapterException::throwNew($this->adapterType);
        }

        $adapterFactory = self::getAdaptersAvailable()[$this->adapterType];

        return $adapterFactory::createFromSessionHandler($this->adapterConfig);
    }

    /**
     * @return array
     */
    protected static function getAdaptersAvailable(): array
    {
        return [
            Files::getName() => FilesFactory::class,
            Redis::getName() => RedisFactory::class,
        ];
    }
}
