<?php
namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\CommentsRepositoryInterface;
use Everywhere\Api\Contract\Schema\DataLoaderFactoryInterface;
use Everywhere\Api\Schema\EntityResolver;

class CommentResolver extends EntityResolver
{
    public function __construct(CommentsRepositoryInterface $commentRepository, DataLoaderFactoryInterface $loaderFactory)
    {
        $entityLoader = $loaderFactory->create(function ($ids) use ($commentRepository) {
            return $commentRepository->findByIds($ids);
        });

        parent::__construct($entityLoader);
    }
}
