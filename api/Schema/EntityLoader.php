<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 17.48
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Entities\EntityInterface;
use Everywhere\Api\Contract\Schema\EntityLoaderInterface;
use Overblog\DataLoader\DataLoader as OverblogDataLoader;

class EntityLoader extends OverblogDataLoader implements EntityLoaderInterface
{
    public function load($key)
    {
        if ($key instanceof EntityInterface) {
            return $this->getPromiseAdapter()->createFulfilled($key);
        }

        return parent::load($key);
    }
}