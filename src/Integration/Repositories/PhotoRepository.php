<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 21.10.17
 * Time: 17.22
 */

namespace Everywhere\Oxwall\Integration\Repositories;


use Everywhere\Api\Contract\Integration\PhotoRepositoryInterface;
use Everywhere\Api\Entities\Photo;

class PhotoRepository implements PhotoRepositoryInterface
{
    public function findByIds($ids)
    {
        $items = \PHOTO_BOL_PhotoService::getInstance()->findPhotoListByIdList($ids, 1, count($ids));

        $out = [];
        foreach ($items as $item) {
            $photo = new Photo();
            $photo->id = (int) $item["id"];
            $photo->src = $item["url"];
            $photo->owner = (int) $item["userId"];

            $out[$photo->id] = $photo;
        }

        return $out;
    }
}