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
     * @param array $ids
     * @return array<User>
     */
    public function findByIds($ids);

    /**
     * @return array
     */
    public function findAllIds();

    /**
     * @param $ids
     * @return mixed
     */
    public function findFriends($ids);

    /**
     * @param $ids
     * @return mixed
     */
    public function findComments($ids);

    /**
     * @param $ids
     * @return mixed
     */
    public function findPhotos($ids);
}
