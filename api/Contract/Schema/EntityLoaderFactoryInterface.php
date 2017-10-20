<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 15.12
 */

namespace Everywhere\Api\Contract\Schema;


interface EntityLoaderFactoryInterface
{
    /**
     * @return EntityLoaderInterface
     */
    public function create(EntitySourceInterface $source);
}