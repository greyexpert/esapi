<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 17.00
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\PhotoRepositoryInterface;
use Everywhere\Api\Contract\Schema\DataLoaderFactoryInterface;
use Everywhere\Api\Schema\EntityResolver;

class PhotoResolver extends EntityResolver
{
    /**
     * @var DataLoaderInterface
     */
    protected $commentsLoader;

    public function __construct(PhotoRepositoryInterface $photoRepository, DataLoaderFactoryInterface $loaderFactory)
    {
        $entityLoader = $loaderFactory->create(function ($ids) use ($photoRepository) {
            return $photoRepository->findByIds($ids);
        });

        $this->commentsLoader = $loaderFactory->create(function($ids, $args) use($photoRepository) {
            return $photoRepository->findComments($ids, $args);
        }, []);

        parent::__construct($entityLoader);
    }

    /**
     * @param Photo $photo
     * @param $fieldName
     * @param $args
     * @return mixed|null
     */
    public function resolveField($photo, $fieldName, $args)
    {
        switch ($fieldName) {
            case "comments":
                return $this->commentsLoader->load($photo->id, $args);
                break;

            default:
                return parent::resolveField($photo, $fieldName, $args);
                break;
        }
    }
}