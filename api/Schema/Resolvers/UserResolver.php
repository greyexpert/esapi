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
use Everywhere\Api\Contract\Schema\DataLoaderInterface;
use Everywhere\Api\Entities\User;
use Everywhere\Api\Schema\DataLoader;
use Everywhere\Api\Schema\EntityResolver;

class UserResolver extends EntityResolver
{
    /**
     * @var DataLoaderInterface
     */
    protected $friendListLoader;

    /**
     * @var DataLoaderInterface
     */
    protected $photosLoader;
    
    /**
     * @var DataLoaderInterface
     */
    protected $commentsLoader;

    public function __construct(UsersRepositoryInterface $usersRepository, DataLoaderFactoryInterface $loaderFactory) {
        parent::__construct(
            $loaderFactory->create(function($ids) use($usersRepository) {
                return $usersRepository->findByIds($ids);
            })
        );

        $this->friendListLoader = $loaderFactory->create(function($ids, $args) use($usersRepository) {
            return $usersRepository->findFriends($ids, $args);
        }, []);

        $this->commentsLoader = $loaderFactory->create(function($ids, $args) use($usersRepository) {
            return $usersRepository->findComments($ids, $args);
        }, []);

        $this->photosLoader = $loaderFactory->create(function($ids, $args) use($usersRepository) {
            return $usersRepository->findPhotos($ids, $args);
        }, []);
    }

    /**
     * @param User $user
     * @param $fieldName
     * @param $args
     * @return mixed|null
     */
    public function resolveField($user, $fieldName, $args)
    {
        switch ($fieldName) {
	        case "friends":
	          return $this->friendListLoader->load($user->id, $args);
	          break;
	
	        case "comments":
	          return $this->commentsLoader->load($user->id, $args);
	          break;
	
			case "photos":
	          return $this->photosLoader->load($user->id, $args);
	          break;
	
	        default:
	          return parent::resolveField($user, $fieldName, $args);
	          break;
	    }
    }
}