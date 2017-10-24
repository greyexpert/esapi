<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 18.05
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Entities\EntityInterface;
use Everywhere\Api\Contract\Integration\EntitySourceInterface;
use Everywhere\Api\Contract\Schema\DataLoaderInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

class EntityResolver implements ResolverInterface
{
    /**
     * @var DataLoaderInterface
     */
    protected $entityLoader;

    public function __construct(DataLoaderInterface $entityLoader) {
        $this->entityLoader = $entityLoader;
    }

    public function resolve($root, $args, $context, ResolveInfo $info) {
        return $this->entityLoader->load($root)->then(function($entity) use ($info, $args) {
            return $this->resolveField($entity, $info->fieldName, $args);
        });
    }

    /**
     * @param EntityInterface $entity
     * @param $fieldName
     * @param $args
     * @return null|mixed
     */
    protected function resolveField($entity, $fieldName, $args) {
        return isset($entity->{$fieldName})
            ? $entity->{$fieldName}
            : null;
    }
}