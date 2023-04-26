<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Enums\CoralAdoptionActions;
use D4rk0snet\Adoption\Models\AdopteesModel;
use D4rk0snet\Adoption\Service\AdopteeService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use JsonMapper;
use WP_REST_Request;

/**
 * Endpoint pour le nommage des adoptés
 */
class NameAdopteesEndpoint extends APIEnpointAbstract
{
    private const ADOPTION_UUID_PARAM = 'uuid';

    public static function callback(WP_REST_Request $request): \WP_REST_Response
    {
        $payload = json_decode($request->get_body());
        if($payload === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        $mapper = new JsonMapper();
        $adopteesModel = $mapper->map($payload, new AdopteesModel());
        $adoptionUuid = $request->get_param(self::ADOPTION_UUID_PARAM);


        try {
            do_action(CoralAdoptionActions::PENDING_NAMING->value, $adopteesModel);
            AdopteeService::giveNameToAdoptees($adoptionUuid, $adopteesModel);
        } catch (\Exception $exception) {
            return APIManagement::APIError($exception->getMessage(), $exception->getCode());
        }

        return APIManagement::APIOk();
    }

    public static function getMethods(): array
    {
        return ["POST"];
    }

    public static function getPermissions(): string
    {
        return "__return_true";
    }

    public static function getEndpoint(): string
    {
        return "adoption/(?P<".self::ADOPTION_UUID_PARAM.">[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})/names";
    }
}