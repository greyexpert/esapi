<?php

namespace Everywhere\Api;

use Slim\App;
use Everywhere\Api\Middleware\GraphQLMiddleware;

/**
 * @var $app App
 */
$app;

$container = $app->getContainer();

$app->any("/graphql", $container->get(GraphQLMiddleware::class));