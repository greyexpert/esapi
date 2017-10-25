<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 24.10.17
 * Time: 18.09
 */

namespace Everywhere\Api\Contract\Schema;

use GraphQL\Executor\Promise\Promise;

interface DataLoaderInterface
{
    /**
     * @param $key
     * @param array $args
     *
     * @return Promise
     */
    public function load($key, array $args = []);

    /**
     * @param $key
     * @param array $args
     *
     * @return Promise
     */
    public function loadMany($key, array $args = []);

    /**
     * @param $key
     * @param array $args
     *
     * @return DataLoaderInterface
     */
    public function clear($key, array $args = []);

    /**
     * @return DataLoaderInterface
     */
    public function clearAll();

    /**
     * @param $key
     * @param $value
     * @param array $args
     *
     * @return DataLoaderInterface
     */
    public function prime($key, $value, array $args = []);
}