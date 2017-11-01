<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 14.51
 */

namespace Everywhere\Api\Middleware;

use Everywhere\Api\Auth\Identity;
use Everywhere\Api\Contract\Auth\IdentityServiceInterface;
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
    protected $identityStorage;

    /**
     * @var TokenBuilderInterface
     */
    protected $tokenBuilder;

    /**
     * @var IdentityServiceInterface
     */
    protected $identityService;

    public function __construct(
        $options,
        IdentityStorageInterface $identityStorage,
        IdentityServiceInterface $identityService,
        TokenBuilderInterface $tokenBuilder
    )
    {
        $options = [
            "secure" => false,
            "secret" => empty($options["secret"]) ? null : $options["secret"],
            "attribute" => self::ATTRIBUTE_NAME,
            "callback" => function($request, $response, $args) use($identityStorage) {
                $identityStorage->write(
                    empty($args["decoded"]) ? null : $this->createIdentity($args["decoded"])
                );
            }
        ];

        $this->identityStorage = $identityStorage;
        $this->tokenBuilder = $tokenBuilder;
        $this->identityService = $identityService;

        parent::__construct($options);
    }

    protected function createIdentity($tokenData)
    {
        return $this->identityService->create(
            $tokenData->userId,
            $tokenData->iat,
            $tokenData->exp
        );
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

        if (!$this->identityStorage->isEmpty()) {
            $resultResponse = $resultResponse->withHeader(
                $this->getHeader(),
                $this->tokenBuilder->build($this->identityStorage->read())
            );
        }

        return $resultResponse;
    }
}