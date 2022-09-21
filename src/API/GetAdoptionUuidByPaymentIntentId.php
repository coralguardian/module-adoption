<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use WP_REST_Request;
use WP_REST_Response;

class GetAdoptionUuidByPaymentIntentId extends APIEnpointAbstract
{
    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $stripePaymentIntentId = $request->get_param("stripePaymentIntentId");
        if ($stripePaymentIntentId === null) {
            return APIManagement::APIError('Missing stripePaymentIntentId GET parameter', 400);
        }

        /** @var AdoptionEntity $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->findOneBy(['stripePaymentIntentId' => $stripePaymentIntentId]);
        if ($adoptionEntity === null) {
            return APIManagement::APINotFound();
        }

        if (!$adoptionEntity->isPaid()) {
            return APIManagement::APIError("Cette adoption n'a pas encore été réglée", 400);
        }

        return APIManagement::APIOk([
            "uuid" => $adoptionEntity->getUuid()
        ]);
    }

    public static function getMethods(): array
    {
        return ["GET"];
    }

    public static function getPermissions(): string
    {
        return "__return_true";
    }

    public static function getEndpoint(): string
    {
        return "adoption/uuid";
    }
}