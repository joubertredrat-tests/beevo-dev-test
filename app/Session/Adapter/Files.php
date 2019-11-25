<?php

declare(strict_types=1);

namespace App\Session\Adapter;

use App\Exception\Session\Adapter\Files\InvalidConfigException;
use function array_keys;
use function file_exists;
use function file_get_contents;
use function sprintf;
use function unlink;

/**
 * Files
 *
 * @package App\Session\Adapters
 */
class Files implements AdapterInterface
{
    use AdapterConfigTrait;

    /**
     * Adapter name
     */
    const ADAPTER_NAME = 'files';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $config)
    {
        if (!self::isValidConfig($config)) {
            throw InvalidConfigException::throwNew();
        }

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
        $basename = self::getBasename($session_id);
        $path = $this->getPath($basename);

        if (!file_exists($path)) {
            return true;
        }

        return unlink($path);
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
        $basename = self::getBasename($session_id);
        $path = $this->getPath($basename);

        if (!file_exists($path)) {
            return '';
        }

        return file_get_contents($path);
    }

    /**
     * {@inheritDoc}
     */
    public function write($session_id, $session_data)
    {
        $basename = self::getBasename($session_id);
        $path = $this->getPath($basename);

        return (bool) file_put_contents($path, $session_data);
    }

    /**
     * @param string $basename
     * @return string
     */
    private function getPath(string $basename): string
    {
        return sprintf('%s/%s', $this->getConfig()['path'], $basename);
    }

    /**
     * {@inheritDoc}
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
            'path' => null,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function isValidConfig(array $config): bool
    {
        return array_keys(self::getConfigSchema()) === array_keys($config);
    }

    /**
     * @param string $session_id
     * @return string
     */
    private static function getBasename(string $session_id): string
    {
        return sprintf('sess_%s', $session_id);
    }
}
