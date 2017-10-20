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

    public function findByIdList($idList)
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
        return \BOL_UserService::getInstance()->findLatestUserIdsList(0, 100);
    }

    public function __destruct()
    {
        $this->counter;
    }
}