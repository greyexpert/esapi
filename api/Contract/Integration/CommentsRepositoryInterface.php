<?php
namespace Everywhere\Api\Contract\Integration;

use Everywhere\Api\Contract\Schema\EntitySourceInterface;

interface CommentsRepositoryInterface
{
    /**
     * @param array $ids
     * @return array<Comment>
     */
    public function findByIds($ids);
}