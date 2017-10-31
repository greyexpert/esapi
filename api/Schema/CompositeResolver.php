<?php

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ContextInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

class CompositeResolver implements ResolverInterface
{
    protected $resolvers = [];
    protected $defaultResolver;

    public function __construct(array $resolvers = [])
    {
        foreach ($resolvers as $fieldName => $resolver) {
            $this->addFieldResolver($fieldName, $resolver);
        }
    }

    protected function addFieldResolver($fieldName, callable $resolver)
    {
        $this->resolvers[$fieldName] = $resolver;
    }

    protected function resolveField($root, $fieldName, $args, ContextInterface $context) {
        if (empty($this->resolvers[$fieldName])) {
            return null;
        }

        return call_user_func($this->resolvers[$fieldName], $root, $args, $context);
    }

    public function resolve($root, $args, ContextInterface $context, ResolveInfo $info)
    {
        return $this->resolveField($root, $info->fieldName, $args, $context);
    }
}