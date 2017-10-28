<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.24
 */

namespace Everywhere\Api\Contract\Integration;

interface UsersRepositoryInterface extends AuthRepositoryInterface
{
    /**
     * @param array $ids
     * @return array<User>
     */
    public function findByIds($ids);

    /**
     * @param array $args
     * @return array
     */
    public function findAllIds(array $args);

    /**
     * @param $ids
     * @param $args
     * @return mixed
     */
    public function findFriends($ids, array $args);

    /**
     * @param $ids
     * @param $args
     * @return mixed
     */
    public function findPhotos($ids, array $args);
}
