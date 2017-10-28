<?php

namespace Everywhere\Api\Middleware;

use Everywhere\Api\Contract\Auth\AuthenticationStorageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    /**
     * @var AuthenticationStorageInterface
     */
    protected $authStorage;

    public function __construct(AuthenticationStorageInterface $storage)
    {
        $this->authStorage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $tokenData = $request->getAttribute(JwtMiddleware::ATTRIBUTE_NAME);
        $this->authStorage->write($tokenData);

        return $next($request, $response);
    }
}