<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\RedirectionStep;
use D4rk0snet\CoralCustomer\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use WP_Query;

class RedirectionService
{
    public static function buildRedirectionUrl(AdoptionEntity $adoptionEntity): string
    {
        $baseUrl = $adoptionEntity->getCustomer() instanceof CompanyCustomerEntity ? home_url("adoption-entreprise") : home_url("adopte-corail");
        $baseUrl .= $adoptionEntity->getLang() === Language::EN ? '/en/': "";
        $baseUrl .=  "?adoptionUuid=" . $adoptionEntity->getUuid() .
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