<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 16.21
 */

namespace Everywhere\Api\Contract\Integration;

interface IntegrationInterface
{
    /**
     * @return UsersRepositoryInterface
     */
    public function getUsersRepository();

    /**
     * @return PhotoRepositoryInterface
     */
    public function getPhotoRepository();

    /**
     * @return CommentsRepositoryInterface
     */
    public function getCommentsRepository();

    /**
     * @return AvatarRepositoryInterface
     */
    public function getAvatarRepository();
}
