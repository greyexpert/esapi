<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 15.16
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Contract\Schema\DataLoaderFactoryInterface;
use Everywhere\Api\Entities\User;
use Everywhere\Api\Schema\DataLoader;
use Everywhere\Api\Schema\EntityResolver;

class UserResolver extends EntityResolver
{
    /**
     * @var DataLoader
     */
    protected $friendListLoader;

    public function __construct(UsersRepositoryInterface $usersRepository, DataLoaderFactoryInterface $loaderFactory) {
        parent::__construct(
            $loaderFactory->create(function($idList) use($usersRepository) {
                return $usersRepository->findByIdList($idList);
            })
        );

        $this->friendListLoader = $loaderFactory->create(function($idList) use($usersRepository) {
            return $usersRepository->findFriendIds($idList);
        });
    }

    /**
     * @param User $user
     * @param $fieldName
     * @return mixed|null
     */
    public function resolveField($user, $fieldName)
    {
        if ($fieldName === "friends") {
            return $this->friendListLoader->load($user->id);
        }

        return parent::resolveField($user, $fieldName);
    }
}