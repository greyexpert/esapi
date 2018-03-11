<?php

namespace Everywhere\Oxwall\Integration\Repositories;

use Everywhere\Api\Contract\Integration\AvatarRepositoryInterface;
use Everywhere\Api\Entities\Avatar;

class AvatarRepository implements AvatarRepositoryInterface
{
    /**
     * @var \BOL_AvatarService
     */
    protected $avatarService;

    protected $avatarSizes = [
        "SMALL" => 1,
        "MEDIUM" => 2,
        "BIG" => 3
    ];

    public function __construct()
    {
        $this->avatarService = \BOL_AvatarService::getInstance();
    }

    public function findByIds($ids)
    {
        $avatarDtos = $this->avatarService->findAvatarByIdList($ids);
        $out = [];

        /**
         * @var $avatarDto \BOL_Avatar
         */
        foreach ($avatarDtos as $avatarDto) {
            $out[$avatarDto->id] = new Avatar($avatarDto->id);
        }

        return $out;
    }

    public function getUrls($ids, array $args)
    {
        $size = $this->avatarSizes[$args["size"]];
        $avatarDtos = $this->avatarService->findAvatarByIdList($ids);

        /**
         * @var $avatarDto \BOL_Avatar
         */
        foreach ($avatarDtos as $avatarDto) {
            $out[$avatarDto->id] = $this->avatarService->getAvatarUrlByAvatarDto($avatarDto, $size);
        }

        return $out;
    }
}
