<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.24
 */

namespace Everywhere\Api\Contract\Integration;

use Everywhere\Api\Contract\Schema\EntitySourceInterface;

interface UsersRepositoryInterface
{
    /**
     * @return int
     */
    public function getCurrentUserId();

    /**
     * @param array $idList
     * @return array<User>
     */
    public function findByIdList($idList);

    /**
     * @return array
     */
    public function findAllIds();

    /**
     * @return array
     */
    public function findFriendIds($idList);
}