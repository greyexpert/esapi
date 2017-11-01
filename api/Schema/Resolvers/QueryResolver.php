<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 15.16
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Contract\Schema\ContextInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

class QueryResolver implements ResolverInterface
{
    /**
     * @var UsersRepositoryInterface
     */
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @param $root
     * @param $args
     * @param ContextInterface $context
     * @param ResolveInfo $info
     *
     * @return array|null
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $out = null;

        switch ($info->fieldName) {
            case "me":
                $out = $context->getViewer()->getUserId();
                break;

            case "users":
                $out = $this->usersRepository->findAllIds($args);
                break;
        }

        return $out;
    }
}