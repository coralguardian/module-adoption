<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\RedirectionStep;
use WP_Query;

class RedirectionService
{
    public static function buildRedirectionUrl(AdoptionEntity $adoptionEntity)
    {
        $baseUrl = home_url("adoption-entreprise");
        $baseUrl .=  "?adoptionUuid=" . $adoptionEntity->getUuid() .
            "&step=" . RedirectionStep::getEnumBasedOnClass($adoptionEntity::class)->value;
        $urlParts = parse_url($baseUrl);

        return $urlParts["host"].$urlParts["path"]."?".$urlParts["query"];
    }

}