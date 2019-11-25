<?php

declare(strict_types=1);

namespace App\Session\Adapter;

/**
 * AdapterConfigTrait
 *
 * @package App\Session\Adapter
 */
trait AdapterConfigTrait
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return void
     */
    protected function setConfig(array $config): void
    {
        $this->config = $config;
    }
}
