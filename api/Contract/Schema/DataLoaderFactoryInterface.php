<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 13.40
 */

namespace Everywhere\Api\Contract\Schema;


interface DataLoaderFactoryInterface
{
    public function create(callable $source);
}