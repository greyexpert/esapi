<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 9.53
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

class ResolverDecorator implements ResolverInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->resolver->resolve($root, $args, $context, $info);
    }
}