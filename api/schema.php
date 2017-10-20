<?php

namespace Everywhere\Api;

use Everywhere\Api\Schema\Resolvers\QueryResolver;
use Everywhere\Api\Schema\Resolvers\UserResolver;

return [
    "path" => __DIR__ . "/Schema.graphqls",
    "resolvers" => [
        "Query" => QueryResolver::class,
        "User" => UserResolver::class,
    ]
];