<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.24
 */

namespace Everywhere\Api\Contract\Integration;

use Everywhere\Api\Contract\Schema\EntitySourceInterface;

interface UsersRepositoryInterface extends EntitySourceInterface
{
    /**
     * @return array<User>
     */
    public function findAllIds();

    public function findFriendIds($userId);
}