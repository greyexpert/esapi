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

    /**
     * @var DataLoader
     */
    protected $photosLoader;

    public function __construct(UsersRepositoryInterface $usersRepository, DataLoaderFactoryInterface $loaderFactory) {
        parent::__construct(
            $loaderFactory->create(function($ids) use($usersRepository) {
                return $usersRepository->findByIds($ids);
            })
        );

        $this->friendListLoader = $loaderFactory->create(function($ids) use($usersRepository) {
            return $usersRepository->findFriends($ids);
        });

        $this->photosLoader = $loaderFactory->create(function($ids) use($usersRepository) {
            return $usersRepository->findPhotos($ids);
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

        if ($fieldName === "photos") {
            return $this->photosLoader->load($user->id);
        }

        return parent::resolveField($user, $fieldName);
    }
}