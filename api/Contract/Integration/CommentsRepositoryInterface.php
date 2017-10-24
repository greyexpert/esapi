<?php
namespace Everywhere\Api\Contract\Integration;

interface CommentsRepositoryInterface
{
    /**
     * @param array $ids
     * @return array<Comment>
     */
    public function findByIds($ids);
}
