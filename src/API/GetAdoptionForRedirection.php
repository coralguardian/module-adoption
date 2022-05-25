<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Enums\RedirectionStep;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use WP_REST_Request;
use WP_REST_Response;

class GetAdoptionForRedirection extends APIEnpointAbstract
{
    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $adoptionUuid = $request->get_param("adoptionUuid");
        if ($adoptionUuid === null) {
            return APIManagement::APIError('Missing adoptionUuid GET parameter', 400);
        }

        $step = $request->get_param("step");
        if ($step === null) {
            return APIManagement::APIError('Missing step GET parameter', 400);
        }

        /** @var AdoptionEntity $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(AdoptionEntity::class)->find($adoptionUuid);
        if ($adoptionEntity === null) {
            return APIManagement::APINotFound();
        }

        try {
            $redirectionEnum = RedirectionStep::from($step);
        } catch (\Exception $exception) {
            return APIManagement::APIError('Wrong step', 400);
        }

        $adoptionClass = $redirectionEnum->getAdoptionClass();
        if (!$adoptionEntity instanceof $adoptionClass) {
            return APIManagement::APIError('Wrong step and adoption coordination', 400);
        }

        if (!$adoptionEntity->isPaid()) {
            return APIManagement::APIError("Cette adoption n'a pas encore été réglée", 400);
        }

        return APIManagement::APIOk([
            "uuid" => $adoptionEntity->getUuid(),
            "type" => $adoptionEntity->getAdoptedProduct()->value,
            "quantity" => $adoptionEntity->getQuantity()
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
        return "adoption/redirection";
    }
}