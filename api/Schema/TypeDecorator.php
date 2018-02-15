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
     * @var PromiseAdapter
     */
    protected $promiseAdapter;

    public function __construct(
        array $resolversMap,
        callable $getResolver,
        PromiseAdapter $promiseAdapter
    ) {
        $this->resolversMap = $resolversMap;
        $this->getResolver = $getResolver;
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

    private function decorateField($typeName, $fieldName, $configs) {
        $undefined = Utils::undefined();
        $resolvers = $this->getResolvers($typeName, $fieldName);

        if (empty($resolvers)) {
            return $configs;
        }

        $configs["resolve"] = function($root, $args, $context, ResolveInfo $info) use($undefined, $resolvers) {
            $outPromise = $this->promiseAdapter->createFulfilled($undefined);

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

            return $outPromise;
        };

        return $configs;
    }

    public function decorate(array $typeConfig)
    {
        $typeConfig["fields"] = function() use ($typeConfig) {
            $fields = is_callable($typeConfig["fields"]) ? $typeConfig["fields"]() : $typeConfig["fields"];

            $out = [];
            foreach ($fields as $name => $config) {
                $out[$name] = $this->decorateField($typeConfig["name"], $name, $config);
            }

            return $out;
        };

        return $typeConfig;
    }
}
