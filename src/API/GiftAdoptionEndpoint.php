<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Models\CompanyGiftAdoptionModel;
use D4rk0snet\Adoption\Models\IndividualGiftAdoptionModel;
use D4rk0snet\Adoption\Service\AdoptionService;
use D4rk0snet\Coralguardian\Enums\CustomerType;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use Hyperion\Stripe\Service\StripeService;
use JsonMapper;
use WP_REST_Request;

/**
 * Endpoint pour la création d'une adoption mais qui n'a pas été encore payé.
 * @todo : Blinder en cas d'échec pour ne pas avoir d'inconsistence dans la bdd
 */
class GiftAdoptionEndpoint extends APIEnpointAbstract
{
    public const PAYMENT_INTENT_TYPE = 'gift_adoption';

    public static function callback(WP_REST_Request $request): \WP_REST_Response
    {
        $payload = json_decode($request->get_body(), false, 512, JSON_THROW_ON_ERROR);
        if ($payload === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        try {
            $customerType = CustomerType::from($payload->customer_type);
        } catch (\ValueError $exception) {
            return APIManagement::APIError("Undefined Customer type", 400);
        }

        switch ($customerType) {
            case CustomerType::INDIVIDUAL:
                $model = new IndividualGiftAdoptionModel();
                break;
            case CustomerType::COMPANY:
                $model = new CompanyGiftAdoptionModel();
                break;
        }

        try {
            $mapper = new JsonMapper();
            $mapper->bExceptionOnMissingData = true;
            $giftAdoptionModel = $mapper->map($payload, $model);
        } catch (\Exception $exception) {
            return APIManagement::APIError($exception->getMessage(), 400);
        }

        switch ($customerType) {
            case CustomerType::INDIVIDUAL:
                $uuid = AdoptionService::createIndividualGiftAdoption($giftAdoptionModel);
                break;
            case CustomerType::COMPANY:
                $uuid = AdoptionService::createCompanyGiftAdoption($giftAdoptionModel);
                break;
        }

        $paymentIntent = AdoptionService::createInvoiceAndGetPaymentIntent($giftAdoptionModel);

        // Add Order id to paymentintent
        StripeService::addMetadataToPaymentIntent($paymentIntent, [
            'gift_adoption_uuid' => $uuid,
            'type' => self::PAYMENT_INTENT_TYPE
        ]);

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
        return "adoption/gift";
    }
}
