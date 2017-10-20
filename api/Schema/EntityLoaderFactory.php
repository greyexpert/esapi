<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 17.07
 */

namespace Everywhere\Api\Schema;


use Everywhere\Api\Contract\Entities\EntityInterface;
use Everywhere\Api\Contract\Schema\EntityLoaderFactoryInterface;
use Everywhere\Api\Contract\Schema\EntitySourceInterface;
use GraphQL\Executor\Promise\PromiseAdapter;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class EntityLoaderFactory implements EntityLoaderFactoryInterface
{
    protected $promiseAdapter;

    public function __construct(PromiseAdapter $promiseAdapter)
    {
        $this->promiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($promiseAdapter);
    }

    public function create(EntitySourceInterface $source)
    {
        return new EntityLoader(function($ids) use ($source) {
            return $this->retrieveEntities($source, $ids);
        }, $this->promiseAdapter);
    }

    protected function retrieveEntities(EntitySourceInterface $source, $ids) {
        $entities = $source->findByIdList($ids);
        $byIdList = array_flip($ids);

        /**
         * @var $entity EntityInterface
         */
        foreach ($entities as $entity) {
            $byIdList[$entity->getId()] = $entity;
        }

        return $this->promiseAdapter->createFulfilled(array_values($byIdList));
    }
}