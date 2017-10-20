<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 15.16
 */

namespace Everywhere\Api\Schema\Resolvers;

use Everywhere\Api\Contract\Integration\UsersRepositoryInterface;
use Everywhere\Api\Contract\Schema\ResolverInterface;
use Everywhere\Api\Entities\User;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Executor\Promise\PromiseAdapter;
use GraphQL\Type\Definition\ResolveInfo;

use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;


class UserResolver implements ResolverInterface
{
    /**
     * @var UsersRepositoryInterface
     */
    protected $usersRepository;

    /**
     * @var DataLoader
     */
    protected $userLoader;

    /**
     * @var WebonyxGraphQLSyncPromiseAdapter
     */
    protected $promiseAdapter;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;

        $graphQLPromiseAdapter = new SyncPromiseAdapter();
        $promiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($graphQLPromiseAdapter);

        $this->userLoader = new DataLoader(function($ids) use($usersRepository, $promiseAdapter) {
            return $promiseAdapter->createAll(array_map(function($id) use($usersRepository, $promiseAdapter) {
                return $promiseAdapter->createFulfilled($usersRepository->findById($id));
            }, $ids));
        }, $promiseAdapter);

        $this->promiseAdapter = $promiseAdapter;
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $promise = $root instanceof User
            ? $this->promiseAdapter->createFulfilled($root)
            : $this->userLoader->load($root);

        $user = $this->promiseAdapter->await($promise);

        return $user->{$info->fieldName};
    }
}