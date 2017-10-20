<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 15.16
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use Everywhere\Api\Entities\User;
use GraphQL\Type\Definition\ResolveInfo;

class UserResolver implements ResolverInterface
{
    /**
     * @var UsersRepositoryInterface
     */
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $user = $root instanceof User ? $root : $this->usersRepository->findById($root);

        return $user->{$info->fieldName};
    }
}