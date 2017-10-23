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
    public function __construct(PhotoRepositoryInterface $photoRepository, DataLoaderFactoryInterface $loaderFactory)
    {
        $entityLoader = $loaderFactory->create(function ($ids) use ($photoRepository) {
            return $photoRepository->findByIds($ids);
        });

        parent::__construct($entityLoader);
    }
}