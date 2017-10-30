<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 14.51
 */

namespace Everywhere\Api\Middleware;

use Psr\Http\Message\RequestInterface;
use Slim\Middleware\JwtAuthentication;

class JwtMiddleware extends JwtAuthentication
{
    const ATTRIBUTE_NAME = "token";

    public function __construct($options)
    {
        $options = [
            "secure" => false,
            "secret" => empty($options["secret"]) ? null : $options["secret"],
            "attribute" => self::ATTRIBUTE_NAME
        ];

        parent::__construct($options);
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
}