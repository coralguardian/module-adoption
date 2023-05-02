<?php

namespace D4rk0snet\Adoption\Service;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\RedirectionStep;
use D4rk0snet\CoralCustomer\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use WP_Query;

class RedirectionService
{
    public static function buildRedirectionUrl(AdoptionEntity $adoptionEntity): string
    {
        if($adoptionEntity->getLang() === Language::EN) {
            $baseUrl = $adoptionEntity->getCustomer() instanceof CompanyCustomerEntity ? home_url()."/en/csr-coral-guardian": home_url()."/en/adopt-a-coral";
        } else {
            $baseUrl = $adoptionEntity->getCustomer() instanceof CompanyCustomerEntity ? home_url("adoption-entreprise") : home_url("adopte-corail");
        }
        // Temp
        $baseUrl = home_url()."/deposit";
        $c = $adoptionEntity->getCustomer() instanceof CompanyCustomerEntity ? "company" : "individual";
        $action = $adoptionEntity instanceof GiftAdoption ? "gift" : "adoption";
        $project = $adoptionEntity->getProject()->value;

        $baseUrl .=  "?adoptionUuid=" . $adoptionEntity->getUuid() . "&c=$c&action=$action&project=$project";

        return $baseUrl;
    }

    public static function buildRedirectionUrlWithoutHost(AdoptionEntity $adoptionEntity): string
    {
        $baseUrl = self::buildRedirectionUrl($adoptionEntity);
        $urlParts = parse_url($baseUrl);
        return $urlParts["host"].$urlParts["path"]."?".$urlParts["query"];
    }

}