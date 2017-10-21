<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 12.57
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\DataLoaderFactoryInterface;
use GraphQL\Executor\Promise\PromiseAdapter;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class DataLoaderFactory implements DataLoaderFactoryInterface
{
    protected $promiseAdapter;

    public function __construct(PromiseAdapter $promiseAdapter)
    {
        $this->promiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($promiseAdapter);
    }

    public function create(callable $source)
    {
        return new DataLoader(function($ids) use ($source) {
            return $this->retrieveEntities($source, $ids);
        }, $this->promiseAdapter);
    }

    protected function retrieveEntities(callable $source, $ids) {
        $dataList = $source($ids);
        $byIdList = array_flip($ids);

        foreach ($dataList as $id => $data) {
            if (array_key_exists($id, $byIdList)) {
                $byIdList[$id] = $data;
            }
        }

        return $this->promiseAdapter->createFulfilled(array_values($byIdList));
    }
}