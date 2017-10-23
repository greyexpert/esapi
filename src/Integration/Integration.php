<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 17.37
 */

namespace Everywhere\Oxwall\Integration;

use Everywhere\Api\Contract\Integration\IntegrationInterface;
use Everywhere\Oxwall\Integration\Repositories\UsersRepository;
use Everywhere\Oxwall\Integration\Repositories\CommentsRepository;
use Everywhere\Oxwall\Integration\Repositories\PhotoRepository;

class Integration implements IntegrationInterface
{
    public function getUsersRepository()
    {
        return new UsersRepository();
    }

    public function getCommentsRepository()
    {
        return new CommentsRepository();
    }

    public function getPhotoRepository()
    {
        return new PhotoRepository();
    }
}
