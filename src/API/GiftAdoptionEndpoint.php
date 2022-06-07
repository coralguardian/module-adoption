<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\Adoption\Service\AdoptionService;
use D4rk0snet\Coralguardian\Entity\CompanyCustomerEntity;
use D4rk0snet\Coralguardian\Event\BankTransferPayment;
use D4rk0snet\Coralguardian\Event\GiftCodeSent;
use D4rk0snet\Donation\Enums\PaymentMethod;
use Hyperion\Doctrine\Service\DoctrineService;
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
            $mapper = new JsonMapper();
            $mapper->bExceptionOnMissingData = true;
            $giftAdoptionModel = $mapper->map($payload, new GiftAdoptionModel());
        } catch (\Exception $exception) {
            return APIManagement::APIError($exception->getMessage(), 400);
        }

        $giftAdoption = AdoptionService::createGiftAdoption($giftAdoptionModel);
        $uuid = $giftAdoption->getUuid();

        if ($giftAdoption->getPaymentMethod() === PaymentMethod::BANK_TRANSFER) {
            BankTransferPayment::sendEvent($giftAdoption);
            DoctrineService::getEntityManager()->commit();

            return APIManagement::APIOk(["uuid" => $uuid]);
        }

        $paymentIntent = AdoptionService::createInvoiceAndGetPaymentIntent($giftAdoptionModel);

        // Add Order id to paymentintent
        StripeService::addMetadataToPaymentIntent($paymentIntent, [
            'gift_adoption_uuid' => $uuid,
            'type' => self::PAYMENT_INTENT_TYPE
        ]);

        $data = [
            "uuid" => $uuid,
            "clientSecret" => $paymentIntent->client_secret
        ];

        if ($giftAdoption->getGiftCodes()->count() === 1) {
            $data["giftCode"] = $giftAdoption->getGiftCodes()->first()->getGiftCode();
        }

        return APIManagement::APIOk($data);
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
