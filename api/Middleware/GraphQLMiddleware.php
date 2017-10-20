<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 9.10.17
 * Time: 17.24
 */

namespace Everywhere\Api\Middleware;

use Everywhere\Api\Contract\Schema\BuilderInterface;
use GraphQL\Server\ServerConfig;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

use GraphQL\Server\StandardServer;

class GraphQLMiddleware
{
    /**
     * @var StandardServer
     */
    private $server;

    public function __construct(ServerConfig $serverConfigs)
    {
        $this->server = new StandardServer($serverConfigs);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->server->processPsrRequest($request, $response, $response->getBody());
    }
}