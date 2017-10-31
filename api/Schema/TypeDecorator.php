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

class TypeDecorator implements TypeConfigDecoratorInterface
{
    protected $resolversMap;

    /**
     * @var callable
     */
    protected $getResolver;

    public function __construct(array $resolversMap, callable $getResolver)
    {
        $this->resolversMap = $resolversMap;
        $this->getResolver = $getResolver;
    }

    public function decorate(array $typeConfig)
    {
        $name = $typeConfig["name"];

        if (empty($this->resolversMap[$name])) {
            return $typeConfig;
        }

        $resolverClasses = is_array($this->resolversMap[$name])
            ? $this->resolversMap[$name]
            : [ $this->resolversMap[$name] ];

        $resolvers = array_map($this->getResolver, $resolverClasses);

        $typeConfig["resolveField"] = function($root, $args, $context, $info) use($resolvers) {
            $out = null;

            /**
             * @var $resolver ResolverInterface
             */
            foreach ($resolvers as $resolver) {
                if (!$resolver || !$resolver instanceof ResolverInterface) {
                    continue;
                }

                $value = $resolver->resolve($root, $args, $context, $info);

                if ($value !== null) {
                    $out = $value;
                }
            }

            return $out;
        };

        return $typeConfig;
    }
}