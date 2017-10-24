<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 17.38
 */

namespace Everywhere\Oxwall\Integration\Repositories;


use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Entities\User;

class UsersRepository implements UsersRepositoryInterface
{
    public $counter = 0;

    public function getCurrentUserId()
    {
        return 17;
    }

    public function findByIds($idList)
    {
        $this->counter++;

        $userDtoList = \BOL_UserService::getInstance()->findUserListByIdList($idList);

        $users = [];

        /**
         * @var $userDto \BOL_User
         */
        foreach ($userDtoList as $userDto) {
            $user = new User();

            $user->id = $userDto->id;
            $user->name = \BOL_UserService::getInstance()->getDisplayName($userDto->id);
            $user->email = $userDto->email;

            $users[$userDto->id] = $user;
        }

        return $users;
    }

    public function findAllIds()
    {
        $this->counter++;

        return \BOL_UserService::getInstance()->findLatestUserIdsList(0, 100);
    }

    public function findFriends($userIds, array $args)
    {
        $this->counter++;
        $out = [];
        foreach ($userIds as $userId) {
            $out[$userId] =  \FRIENDS_BOL_Service::getInstance()->findFriendIdList($userId, $args["offset"], $args["count"]);
        }

        return $out;
    }

    public function findPhotos($ids, array $args)
    {
        $items = \PHOTO_BOL_PhotoService::getInstance()->findPhotoListByUserIdList($ids, 1, $args["count"]);
        $out = [];

        foreach ($items as $item) {
            $userId = (int) $item["userId"];
            $userItems = empty($out[$userId]) ? [] : $out[$userId];

            $userItems[] = $item["id"];

            $out[$userId] = $userItems;
        }

        return $out;
    }

    public function __destruct()
    {
        $this->counter;
    }
}