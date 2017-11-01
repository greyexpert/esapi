<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 11.56
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ResolverInterface;
use Everywhere\Api\Contract\Schema\TypeConfigDecoratorInterface;
use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Utils\Utils;

class TypeDecorator implements TypeConfigDecoratorInterface
{
    protected $resolversMap;

    /**
     * @var callable
     */
    protected $getResolver;

    /**
     * @var ResolverInterface
     */
    protected $defaultResolver;

    public function __construct(array $resolversMap, callable $getResolver, ResolverInterface $defaultResolver)
    {
        $this->resolversMap = $resolversMap;
        $this->getResolver = $getResolver;
        $this->defaultResolver = $defaultResolver;
    }

    protected function getResolvers($typeName, $fieldName)
    {
        $resolvers = [];

        if (isset($this->resolversMap[$typeName . "." . $fieldName])) {
            $resolvers = $this->resolversMap[$typeName . "." . $fieldName];
        } else if(isset($this->resolversMap[$typeName])) {
            $resolvers = $this->resolversMap[$typeName];
        }

        $resolvers = is_array($resolvers) ? $resolvers : [ $resolvers ];

        return array_map($this->getResolver, $resolvers) ?: [ $this->defaultResolver ];
    }

    public function decorate(array $typeConfig)
    {
        $typeConfig["resolveField"] = function($root, $args, $context, ResolveInfo $info) {
            $out = Utils::undefined();
            $resolvers = $this->getResolvers($info->parentType->name, $info->fieldName);

            /**
             * @var $resolver ResolverInterface
             */
            foreach ($resolvers as $resolver) {
                if (!$resolver || !$resolver instanceof ResolverInterface) {
                    throw new InvariantViolation(
                        "Resolver for `" . Utils::printSafe($info->parentType) . "` type was not found or invalid"
                    );
                }

                $value = $resolver->resolve($root, $args, $context, $info);

                if ($value !== Utils::undefined()) {
                    $out = $value;
                }
            }

            return $out === Utils::undefined()
                ? $this->defaultResolver->resolve($root, $args, $context,  $info)
                : $out;
        };

        return $typeConfig;
    }
}