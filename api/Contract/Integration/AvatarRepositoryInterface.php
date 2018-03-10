<?php

namespace Everywhere\Api\Contract\Integration;

interface AvatarRepositoryInterface
{
    public function findByIds($ids);

    public function getUrls($ids, array $args);
}
