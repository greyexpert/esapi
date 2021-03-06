<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 17.37
 */

namespace Everywhere\Oxwall\Integration;

use Everywhere\Api\Contract\Integration\IntegrationInterface;
use Everywhere\Oxwall\Integration\Repositories\AvatarRepository;
use Everywhere\Oxwall\Integration\Repositories\UsersRepository;
use Everywhere\Oxwall\Integration\Repositories\PhotoRepository;
use Everywhere\Oxwall\Integration\Repositories\CommentRepository;

class Integration implements IntegrationInterface
{
    public function getUsersRepository()
    {
        return new UsersRepository();
    }

    public function getPhotoRepository()
    {
        return new PhotoRepository();
    }

    public function getCommentsRepository()
    {
        return new CommentRepository();
    }

    public function getAvatarRepository()
    {
        return new AvatarRepository();
    }
}
