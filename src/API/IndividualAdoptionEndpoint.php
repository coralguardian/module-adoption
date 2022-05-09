<?php

use D4rk0snet\Adoption\Models\AdoptionModel;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;

/**
 * Endpoint pour la création d'une adoption mais qui n'a pas été encore payé.
 */
class IndividualAdoptionEndpoint extends APIEnpointAbstract
{
    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $payload = json_decode($request->get_body());
        if($payload === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        $mapper = new JsonMapper();
        $adoptionModel = $mapper->map($payload, new AdoptionModel());

        $uuid = AdoptionService::createAdoption($adoptionModel);
        $paymentIntent = AdoptionService::createInvoiceAndGetPaymentIntent($adoptionModel);

        return APIManagement::APIOk([
            "uuid" => $uuid,
            "clientSecret" => $paymentIntent->client_secret
        ]);
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
        return "adoption/individual";
    }
}