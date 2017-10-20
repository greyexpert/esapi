<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 18.20
 */

namespace Everywhere\Api\Contract\Schema;


use GraphQL\Executor\Promise\Promise;

interface EntityLoaderInterface
{
    /**
     * Loads a key, returning a `Promise` for the value represented by that key.
     *
     * @param string $key
     *
     * @return Promise
     */
    public function load($key);
}