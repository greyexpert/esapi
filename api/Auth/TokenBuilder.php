<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 30.10.17
 * Time: 16.22
 */

namespace Everywhere\Api\Auth;

use Everywhere\Api\Contract\Auth\TokenBuilderInterface;
use Firebase\JWT\JWT;

class TokenBuilder implements TokenBuilderInterface
{
    protected $options = [
        "lifeTime" => 600, // 10 min
        "secret" => null
    ];

    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function build($identity, $payload = null)
    {
        return JWT::encode($this->buildPayload([
            "identity" => $identity,
            "payload" => $payload
        ]), $this->options["secret"]);
    }

    protected function buildPayload($payload) {
        $currentTime = time();

        return [
            "iat" => time(),
            "exp" => $currentTime + $this->options["lifeTime"],
            "data" => $payload
        ];
    }
}