<?php

namespace Everywhere\Api;

use Everywhere\Api\App\App;
use Everywhere\Api\Contract\Integration\IntegrationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Server
{
    protected $baseUrl;

    /**
     * @var IntegrationInterface
     */
    protected $integration;

    public function __construct($baseUrl, IntegrationInterface $integration)
    {
        $this->baseUrl = $baseUrl;
        $this->integration = $integration;
    }

    private function init() {
        $app = new App(
            $this->integration,
            require __DIR__ . '/dependencies.php',
            require __DIR__ . '/configs.php'
        );

        require __DIR__ . '/routes.php';

        return $app;
    }

    public function run($path) {

        /**
         * @var App
         */
        $app = $this->init();

        /**
         * @var ResponseInterface $response
         */
        $response = $app->getContainer()->get("response");

        /**
         * @var ServerRequestInterface $request
         */
        $request = $app->getContainer()->get("request");

        /**
         * @var UriInterface $uri
         */
        $uri = $request->getUri()->withPath($path);
        $request = $request->withUri($uri->withBasePath($this->baseUrl));
        $response = $app->process($request, $response);

        $app->respond($response);
    }
}