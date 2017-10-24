<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 12.57
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\DataLoaderInterface;
use Overblog\DataLoader\DataLoader as OverblogDataLoader;

class DataLoaderDecorator implements DataLoaderInterface
{
    /**
     * @var OverblogDataLoader
     */
    protected $loader;

    /**
     * @var callable
     */
    protected $keyBuilder;

    public function __construct(OverblogDataLoader $loader, callable $keyBuilder)
    {
        $this->loader = $loader;
        $this->keyBuilder = $keyBuilder;
    }

    protected function buildKey($key, array $args = []) {
        return call_user_func($this->keyBuilder, $key, $args);
    }

    public function load($key, array $args = [])
    {
        return $this->loader->load(
            $this->buildKey($key, $args)
        );
    }

    public function loadMany($keys, array $args = [])
    {
        return $this->loader->loadMany(array_map(function($key) use($args) {
            return $this->buildKey($key, $args);
        }, $keys));
    }

    public function clear($key, array $args = [])
    {
        $this->loader->clear(
            $this->buildKey($key, $args)
        );

        return $this;
    }

    public function clearAll()
    {
        $this->loader->clearAll();

        return $this;
    }
}