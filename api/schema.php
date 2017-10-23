<?php
namespace Everywhere\Api;

use Everywhere\Api\Schema\Resolvers\QueryResolver;
use Everywhere\Api\Schema\Resolvers\UserResolver;
use Everywhere\Api\Schema\Resolvers\CommentResolver;
use Everywhere\Api\Schema\Resolvers\PhotoResolver;

return [
    "path" => __DIR__ . "/Schema.graphqls",
    "resolvers" => [
        "Query" => QueryResolver::class,
        "User" => UserResolver::class,
        "Comment" => CommentResolver::class,
        "Photo" => PhotoResolver::class,
    ]
];
