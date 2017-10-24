<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 17.03
 */

namespace Everywhere\Api\Contract\Integration;


interface PhotoRepositoryInterface
{
    /**
     * @param $ids
     * @return mixed
     */
    public function findByIds($ids);

    /**
     * @param $ids
     * @return mixed
     */
    public function findComments($ids, array $args);
}
