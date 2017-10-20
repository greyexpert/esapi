<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 20.10.17
 * Time: 15.30
 */

namespace Everywhere\Api\Contract\Schema;


interface EntitySourceInterface
{
    /**
     * @param array $idList
     * @return array
     */
    public function findByIdList(array $idList);
}