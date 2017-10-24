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
        return new DataLoader(function($keys) use ($source) {
            $out = array_fill(0, count($keys), null);
            $buckets = $this->parseKeys($keys);

            foreach ($buckets as $bucket) {
                list($ids, $args, $bucketKeyIndexes) = $bucket;
                $bucketData = $this->retrieveItems($source, $ids, $args);

                foreach ($bucketKeyIndexes as $index => $keyIndex) {
                    $out[$keyIndex] = $bucketData[$index];
                }
            }

            return $this->promiseAdapter->createFulfilled(array_values($out));
        }, $this->promiseAdapter);
    }

    protected function parseKey($key) {
        $id = null;
        $args = [];

        if (is_array($key)) { // contains arguments
            $id = empty($key[0]) ? null : $key[0];
            $args = empty($key[1]) ? [] : $key[1];
        } else { // simple key = id
            $id = $key;
        }

        return [$id, $args];
    }

    protected function parseKeys(array $keys) {
        $byArgs = [];
        foreach ($keys as $index => $key) {
            list($id, $args) = $this->parseKey($key);
            $argsKey = json_encode($args);
            $byArgs[$argsKey] = empty($byArgs[$argsKey]) ? [[], $args, []] : $byArgs[$argsKey];

            $byArgs[$argsKey][0][] = $id;
            $byArgs[$argsKey][2][] = $index;
        }

        return array_values($byArgs);
    }

    protected function retrieveItems(callable $source, $ids, $args = []) {
        $dataList = $source($ids, $args);
        $byIdList = array_flip($ids);

        foreach ($dataList as $id => $data) {
            if (array_key_exists($id, $byIdList)) {
                $byIdList[$id] = $data;
            }
        }

        return array_values($byIdList);
    }
}