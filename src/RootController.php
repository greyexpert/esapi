<?php

namespace Everywhere\Oxwall;

use Everywhere\Api\Server;
use Everywhere\Oxwall\Integration\Integration;

class RootController extends \OW_ActionController
{
    public function __construct()
    {

    }

    public function index($params) {
        $baseUrl = \OW::getRouter()->urlForRoute("everywhere-api");
        $server = new Server($baseUrl, new Integration());

        $server->run($params[RootRoute::PATH_ATTR]);

        exit;
    }
}