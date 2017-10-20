<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.24
 */

namespace Everywhere\Api\Contract\Integration;

interface UsersRepositoryInterface
{
    /**
     * @param $idList
     * @return array<User>
     */
    public function findByIdList($idList);

    /**
     * @return array<User>
     */
    public function findAllIds();
}