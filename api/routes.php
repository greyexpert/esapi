<?php

namespace Everywhere\Api;

use Everywhere\Api\Middleware\AuthMiddleware;
use Slim\App;
use Everywhere\Api\Middleware\GraphQLMiddleware;

/**
 * @var $app App
 */
$app;

$container = $app->getContainer();

$app->add(new AuthMiddleware());

$app->any("/graphql", $container->get(GraphQLMiddleware::class));