<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\CoralCustomer\Entity\CustomerEntity;
use D4rk0snet\Donation\Entity\DonationEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use WP_REST_Request;
use WP_REST_Response;

class GetAdoptionDetails extends APIEnpointAbstract
{
    private const ADOPTION_UUID_PARAM = 'uuid';

    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $adoptionUuid = $request->get_param(self::ADOPTION_UUID_PARAM);

        /** @var AdoptionEntity|GiftAdoption $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(DonationEntity::class)->find($adoptionUuid);
        if ($adoptionEntity === null) {
            return APIManagement::APINotFound();
        }

        return APIManagement::APIOk([
            'order' => [
                'type' => $adoptionEntity instanceof AdoptionEntity ? "regular" : "gift",
                'productType' => $adoptionEntity->getAdoptedProduct()->value,
                'quantity' => $adoptionEntity->getQuantity()
            ],
            'adopter' => [
                'type' => $adoptionEntity->getCustomer() instanceof CustomerEntity ? "individual" : "company"
            ],
            'project' => $adoptionEntity->getProject()->value
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
        return "adoption/(?P<".self::ADOPTION_UUID_PARAM.">[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})/details";
    }
}