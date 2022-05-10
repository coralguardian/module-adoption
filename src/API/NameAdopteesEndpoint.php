<?php

namespace D4rk0snet\Adoption\API;

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
    public static function callback(WP_REST_Request $request): \WP_REST_Response
    {
        $payload = json_decode($request->get_body());
        if($payload === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        $mapper = new JsonMapper();
        $adopteesModel = $mapper->map($payload, new AdopteesModel());

        try {
            AdopteeService::giveNameToAdoptees($adopteesModel);
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
        return "adoption/naming";
    }
}