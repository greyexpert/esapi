<?php
namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\CommentsRepositoryInterface;
use Everywhere\Api\Contract\Schema\DataLoaderFactoryInterface;
use Everywhere\Api\Entities\Comment;
use Everywhere\Api\Schema\DataLoader;
use Everywhere\Api\Schema\EntityResolver;

class CommentResolver extends EntityResolver
{
//    /**
//     * @var DataLoader
//     */
//    protected $commentsLoader;
//
//    public function __construct(CommentsRepositoryInterface $commentsRepository, DataLoaderFactoryInterface $loaderFactory) {
//        parent::__construct(
//            $loaderFactory->create(function($ids) use($commentsRepository) {
//                return CommentsRepositoryInterface->findByIds($ids);
//            })
//        );
//
//        $this->commentsLoader = $loaderFactory->create(function($ids) use($commentsRepository) {
//            return CommentsRepositoryInterface->findCommentsIds($ids);
//        });
//    }
//
//    /**
//     * @param Comment $comment
//     * @param $fieldName
//     * @return mixed|null
//     */
//    public function resolveField($comment, $fieldName)
//    {
//        if ($fieldName === "comments") {
//            return $this->commentsLoader->load($comment->id);
//        }
//
//        return parent::resolveField($comment, $fieldName);
//    }
}