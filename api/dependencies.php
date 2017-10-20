<?php

namespace Everywhere\Api;

use Everywhere\Api\Contract\App\ContainerInterface;
use Everywhere\Api\Middleware\GraphQLMiddleware;
use Everywhere\Api\Schema\Resolvers\QueryResolver;

use Everywhere\Api\Contract\Schema\BuilderInterface;
use Everywhere\Api\Contract\Schema\TypeConfigDecoratorInterface;
use Everywhere\Api\Schema\Builder;
use Everywhere\Api\Schema\Resolvers\UserResolver;
use Everywhere\Api\Schema\TypeDecorator;

return [
    TypeConfigDecoratorInterface::class => function(ContainerInterface $container) {
        return new TypeDecorator(
            $container->getSettings()["schema"]["resolvers"],
            function($resolverClass) use ($container) {
                return $container->has($resolverClass) ? $container->get($resolverClass) : null;
            }
        );
    },

    BuilderInterface::class => function(ContainerInterface $container) {
        return new Builder(
            $container->getSettings()["schema"]["path"],
            $container->get(TypeConfigDecoratorInterface::class)
        );
    },

    GraphQLMiddleware::class => function(ContainerInterface $container) {
        return new GraphQLMiddleware(
            $container->get(BuilderInterface::class)
        );
    },

    // Resolvers

    QueryResolver::class => function(ContainerInterface $container) {
        return new QueryResolver($container->getIntegration()->getUsersRepository());
    },

    UserResolver::class => function(ContainerInterface $container) {
        return new UserResolver($container->getIntegration()->getUsersRepository());
    }
];