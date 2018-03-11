<?php
namespace Everywhere\Api;

use Everywhere\Api\Schema\Resolvers\AuthenticationResolver;
use Everywhere\Api\Schema\Resolvers\AvatarResolver;
use Everywhere\Api\Schema\Resolvers\QueryResolver;
use Everywhere\Api\Schema\Resolvers\UserResolver;
use Everywhere\Api\Schema\Resolvers\PhotoResolver;
use Everywhere\Api\Schema\Resolvers\CommentResolver;

return [
    "path" => __DIR__ . "/Schema.graphqls",
    "resolvers" => [
        // Query resolvers

        "Query" => QueryResolver::class,
        "User" => UserResolver::class,
        "Photo" => PhotoResolver::class,
        "Comment" => CommentResolver::class,
        "Avatar" => AvatarResolver::class,

        // Mutation resolvers

        "Mutation" => [
            AuthenticationResolver::class,
        ]
    ]
];
