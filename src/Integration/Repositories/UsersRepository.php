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

    public function findById($id)
    {
        $this->counter++;

        $userDto = \BOL_UserService::getInstance()->findUserById($id);

        $user = new User();

        $user->id = $userDto->id;
        $user->name = \BOL_UserService::getInstance()->getDisplayName($userDto->id);
        $user->email = $userDto->email;

        return $user;
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