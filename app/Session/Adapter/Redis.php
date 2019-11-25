<?php

declare(strict_types=1);

namespace App\Session\Adapter;

use App\Exception\Session\Adapter\Redis\InvalidConfigException;
use Predis\Client;
use Predis\ClientInterface;
use function array_keys;
use function is_null;

/**
 * Redis
 *
 * @package App\Session\Adapters
 */
class Redis implements AdapterInterface
{
    use AdapterConfigTrait;

    /**
     * Adapter name
     */
    const ADAPTER_NAME = 'redis';

    /**
     * @var Client
     */
    protected $redisClient;

    /**
     * Redis constructor
     *
     * @param ClientInterface $redisClient
     * @param array $config
     */
    public function __construct(ClientInterface $redisClient, array $config)
    {
        if (!self::isValidConfig($config)) {
            throw InvalidConfigException::throwNew();
        }

        $this->redisClient = $redisClient;
        $this->setConfig($config);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($session_id)
    {
        $this
            ->redisClient
            ->del([$session_id])
        ;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read($session_id)
    {
        $response = $this
            ->redisClient
            ->get($session_id)
        ;

        if (is_null($response)) {
            return '';
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function write($session_id, $session_data)
    {
        $this
            ->redisClient
            ->set($session_id, $session_data)
        ;

        return true;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::ADAPTER_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public static function getConfigSchema(): array
    {
        return [
            'host' => null,
            'port' => null,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function isValidConfig(array $config): bool
    {
        return array_keys(self::getConfigSchema()) === array_keys($config);
    }
}
