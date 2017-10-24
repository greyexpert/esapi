<?php
namespace Everywhere\Oxwall\Integration\Repositories;

use Everywhere\Api\Contract\Integration\CommentsRepositoryInterface;

class CommentRepository implements CommentsRepositoryInterface
{
    public function findByIds($ids)
    {
        $out = [];

        return $out;
    }
}
