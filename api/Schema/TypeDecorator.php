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
use GraphQL\Executor\Promise\PromiseAdapter;
use GraphQL\Error\InvariantViolation;
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

    /**
     * @var PromiseAdapter
     */
    protected $promiseAdapter;

    public function __construct(
        array $resolversMap,
        callable $getResolver,
        ResolverInterface $defaultResolver,
        PromiseAdapter $promiseAdapter
    ) {
        $this->resolversMap = $resolversMap;
        $this->getResolver = $getResolver;
        $this->defaultResolver = $defaultResolver;
        $this->promiseAdapter = $promiseAdapter;
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

        return array_map($this->getResolver, $resolvers);
    }

    public function decorate(array $typeConfig)
    {
        $undefined = Utils::undefined();

        $typeConfig["resolveField"] = function($root, $args, $context, ResolveInfo $info) use($undefined) {
            $outPromise = $this->promiseAdapter->createFulfilled($undefined);
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

                $valuePromise = $this->promiseAdapter->createFulfilled(
                    $resolver->resolve($root, $args, $context, $info)
                );

                $outPromise = $outPromise->then(function($oldValue) use ($undefined, $valuePromise) {
                    return $valuePromise->then(function($newValue) use ($undefined, $oldValue) {
                        return $newValue === $undefined ? $oldValue : $newValue;
                    });
                });
            }

            return $outPromise->then(function($out) use ($undefined, $root, $args, $context, $info) {
                return $out === $undefined
                    ? $this->defaultResolver->resolve($root, $args, $context, $info)
                    : $out;
            });
        };

        return $typeConfig;
    }
}
