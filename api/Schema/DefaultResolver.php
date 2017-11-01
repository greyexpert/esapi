<?php

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ContextInterface;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;

class DefaultResolver extends AbstractResolver
{
    public function resolve($root, $args, ContextInterface $context, ResolveInfo $info)
    {
        return Executor::defaultFieldResolver($root, $args, $context, $info);
    }
}