<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.24
 */

namespace Everywhere\Api\Contract\Integration;

use Everywhere\Api\Entities\User;

interface UsersRepositoryInterface
{
    /**
     * @param $id
     * @return User
     */
    public function findById($id);

    /**
     * @return array<User>
     */
    public function findAllIds();
}