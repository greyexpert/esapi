<?php

namespace Everywhere\Api;

use Everywhere\Api\Middleware\AuthMiddleware;
use Everywhere\Api\Middleware\JwtMiddleware;
use Slim\App;
use Everywhere\Api\Middleware\GraphQLMiddleware;

/**
 * @var $app App
 */
$app;

$container = $app->getContainer();

$app->add($container[JwtMiddleware::class]);
$app->add($container[AuthMiddleware::class]);

$app->any("/graphql", $container[GraphQLMiddleware::class]);