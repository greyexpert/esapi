<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 14.51
 */

namespace Everywhere\Api\Middleware;

use Everywhere\Api\Auth\Identity;
use Everywhere\Api\Contract\Auth\IdentityStorageInterface;
use Everywhere\Api\Contract\Auth\TokenBuilderInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Middleware\JwtAuthentication;

class AuthenticationMiddleware extends JwtAuthentication
{
    const ATTRIBUTE_NAME = "JWTTokenData";

    /**
     * @var IdentityStorageInterface
     */
    protected $storage;

    /**
     * @var TokenBuilderInterface
     */
    protected $builder;

    public function __construct($options, IdentityStorageInterface $storage, TokenBuilderInterface $builder)
    {
        $options = [
            "secure" => false,
            "secret" => empty($options["secret"]) ? null : $options["secret"],
            "attribute" => self::ATTRIBUTE_NAME,
            "callback" => function($request, $response, $args) use($storage) {
                $storage->write(
                    empty($args["decoded"]) ? null : $this->createIdentity($args["decoded"])
                );
            }
        ];

        $this->storage = $storage;
        $this->builder = $builder;

        parent::__construct($options);
    }

    protected function createIdentity($tokenData)
    {
        $identity = new Identity();
        $identity->issueTime = (int) $tokenData->iat;
        $identity->expirationTime = (int) $tokenData->exp;
        $identity->userId = (int) $tokenData->userId;

        return $identity;
    }

    public function decodeToken($token)
    {
        $decoded = $token ? parent::decodeToken($token) : null;

        /**
         * Return `null` instead of `false` to prevent 401 response
         */
        return $decoded ?: null;
    }

    public function fetchToken(RequestInterface $request)
    {
        /**
         * Return `null` instead of `false` to prevent 401 response
         */
        return parent::fetchToken($request) ?: null;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $resultResponse = parent::__invoke($request, $response, $next);

        if (!$this->storage->isEmpty()) {
            $resultResponse = $resultResponse->withHeader(
                $this->getHeader(),
                $this->builder->build($this->storage->read())
            );
        }

        return $resultResponse;
    }
}