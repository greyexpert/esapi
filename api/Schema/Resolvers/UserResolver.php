<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 15.16
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Entities\EntityInterface;
use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Contract\Schema\EntityLoaderFactoryInterface;
use Everywhere\Api\Entities\User;
use Everywhere\Api\Schema\EntityResolver;


class UserResolver extends EntityResolver
{
    /**
     * @var UsersRepositoryInterface
     */
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository, EntityLoaderFactoryInterface $loaderFactory) {
        parent::__construct(
            $loaderFactory->create($usersRepository)
        );

        $this->usersRepository = $usersRepository;
    }

    /**
     * @param User $user
     * @param $fieldName
     * @return mixed|null
     */
    public function resolveField($user, $fieldName)
    {
        if ($fieldName === "friends") {
            return $this->usersRepository->findFriendIds($user->id);
        }

        return parent::resolveField($user, $fieldName);
    }
}