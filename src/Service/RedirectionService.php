<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\RedirectionStep;
use WP_Query;

class RedirectionService
{
    public static function buildRedirectionUrl(AdoptionEntity $adoptionEntity): string
    {
        $baseUrl = home_url("adoption-entreprise");
        $baseUrl .=  "?stripePaymentIntentId=" . $adoptionEntity->getStripePaymentIntentId() .
            "&step=" . RedirectionStep::getEnumBasedOnClass($adoptionEntity::class)->value;

        return $baseUrl;
    }

    public static function buildRedirectionUrlWithoutHost(AdoptionEntity $adoptionEntity): string
    {
        $baseUrl = self::buildRedirectionUrl($adoptionEntity);
        $urlParts = parse_url($baseUrl);
        return $urlParts["host"].$urlParts["path"]."?".$urlParts["query"];
    }

}