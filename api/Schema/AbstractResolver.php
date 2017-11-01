<?php

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Utils\Utils;

abstract class AbstractResolver implements ResolverInterface
{
    protected function undefined() {
        return Utils::undefined();
    }
}