<?php

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Auth\AuthenticationServiceInterface;
use Everywhere\Api\Contract\Auth\TokenBuilderInterface;
use Everywhere\Api\Contract\Schema\ContextInterface;
use Everywhere\Api\Schema\CompositeResolver;

class AuthenticationResolver extends CompositeResolver
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var TokenBuilderInterface
     */
    protected $tokenBuilder;

    public function __construct(AuthenticationServiceInterface $authService, TokenBuilderInterface $tokenBuilder)
    {
        parent::__construct([
            "signInUser" => [$this, "resolveSignIn"]
        ]);

        $this->authService = $authService;
        $this->tokenBuilder = $tokenBuilder;
    }

    public function resolveSignIn($root, $args, ContextInterface $context) {
        $adapter = $this->authService->getAdapter();
        $adapter->setIdentity($args["login"]);
        $adapter->setCredential($args["password"]);

        $result = $this->authService->authenticate();

        if (!$result->isValid()) {
            return [
                "accessToken" => null,
                "user" => null
            ];
        }

        $identity = $this->authService->getIdentity();

        return [
            "accessToken" => $this->tokenBuilder->build($identity),
            "user" => $identity->userId
        ];
    }
}