<?php

namespace Everywhere\Api;

use Everywhere\Api\Schema\Resolvers\QueryResolver;
use Everywhere\Api\Schema\Resolvers\UserResolver;
use Everywhere\Api\Schema\Resolvers\CommentResolver;

return [
    "path" => __DIR__ . "/Schema.graphqls",
    "resolvers" => [
        "Query" => QueryResolver::class,
        "User" => UserResolver::class,
        "Comment" => CommentResolver::class,
    ]
];