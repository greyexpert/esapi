<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 30.10.17
 * Time: 10.07
 */

namespace Everywhere\Api\Schema\Resolvers;


use Everywhere\Api\Contract\Auth\AuthenticationServiceInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

class MutationResolver implements ResolverInterface
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        if ($info->fieldName === "signInUser") {
            return $this->resolveSignIn($root, $args, $context, $info);
        }
    }

    public function resolveSignIn($root, $args, $context, ResolveInfo $info) {
        $adapter = $this->authService->getAdapter();
        $adapter->setIdentity($args["login"]);
        $adapter->setCredential($args["password"]);

        $result = $this->authService->authenticate();

        if (!$result->isValid()) {
            return [
                "token" => null,
                "user" => null
            ];
        }

        return [
            "token" => "blablatoken",
            "user" => $result->getIdentity()
        ];
    }
}