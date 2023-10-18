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
            $baseUrl = home_url()."/en/envoyez-le-fichier-des-noms-de-vos-coraux/";
        } else {
            $baseUrl = home_url()."/envoyez-le-fichier-des-noms-de-vos-coraux/";
        }
        // Temp
        $c = $adoptionEntity->getCustomer() instanceof CompanyCustomerEntity ? "company" : "individual";
        $action = $adoptionEntity instanceof GiftAdoption ? "gift" : "adoption";
        $project = $adoptionEntity->getProject()->value;

        return $baseUrl."?adoptionUuid=" . $adoptionEntity->getUuid() . "&c=$c&action=$action&project=$project";
    }

    public static function buildRedirectionUrlWithoutHost(AdoptionEntity $adoptionEntity): string
    {
        $baseUrl = self::buildRedirectionUrl($adoptionEntity);
        $urlParts = parse_url($baseUrl);
        return $urlParts["host"].$urlParts["path"]."?".$urlParts["query"];
    }

}