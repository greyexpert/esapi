<?php

namespace Everywhere\Api;

use Everywhere\Api\Contract\App\ContainerInterface;
use Everywhere\Api\Contract\Schema\EntityLoaderFactoryInterface;
use Everywhere\Api\Middleware\GraphQLMiddleware;
use Everywhere\Api\Schema\EntityResolver;
use Everywhere\Api\Schema\EntityLoaderFactory;
use Everywhere\Api\Schema\Resolvers\QueryResolver;
use Everywhere\Api\Contract\Schema\BuilderInterface;
use Everywhere\Api\Contract\Schema\TypeConfigDecoratorInterface;
use Everywhere\Api\Schema\Builder;
use Everywhere\Api\Schema\Resolvers\UserResolver;
use Everywhere\Api\Schema\TypeDecorator;
use GraphQL\Error\UserError;
use GraphQL\Server\ServerConfig;
use GraphQL\Executor\Promise\PromiseAdapter;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;


return [
    PromiseAdapter::class => function() {
        return new SyncPromiseAdapter();
    },

    ServerConfig::class => function(ContainerInterface $container) {
        return ServerConfig::create([
            "schema" => $container[BuilderInterface::class]->build(),
            "promiseAdapter" => $container[PromiseAdapter::class]
        ]);
    },

    BuilderInterface::class => function(ContainerInterface $container) {
        return new Builder(
            $container->getSettings()["schema"]["path"],
            $container[TypeConfigDecoratorInterface::class]
        );
    },

    GraphQLMiddleware::class => function(ContainerInterface $container) {
        return new GraphQLMiddleware(
            $container[ServerConfig::class]
        );
    },

    TypeConfigDecoratorInterface::class => function(ContainerInterface $container) {
        return new TypeDecorator(
            $container->getSettings()["schema"]["resolvers"],
            function($resolverClass) use ($container) {
                return $container->has($resolverClass) ? $container[$resolverClass] : null;
            }
        );
    },

    EntityLoaderFactoryInterface::class => function(ContainerInterface $container) {
        return new EntityLoaderFactory(
            $container[PromiseAdapter::class]
        );
    },

    // Resolvers

    QueryResolver::class => function(ContainerInterface $container) {
        return new QueryResolver($container->getIntegration()->getUsersRepository());
    },

    UserResolver::class => function(ContainerInterface $container) {
        return new UserResolver(
            $container->getIntegration()->getUsersRepository(),
            $container[EntityLoaderFactoryInterface::class]
        );
    }
];