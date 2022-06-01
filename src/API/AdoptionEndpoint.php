<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Models\AdoptionModel;
use D4rk0snet\Adoption\Service\AdoptionService;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Event\BankTransferPayment;
use D4rk0snet\Donation\Enums\PaymentMethod;
use Doctrine\DBAL\Types\ConversionException;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use Hyperion\Stripe\Service\StripeService;
use JsonMapper;
use WP_REST_Request;

/**
 * Endpoint pour la création d'une adoption mais qui n'a pas été encore payé.
 */
class AdoptionEndpoint extends APIEnpointAbstract
{
    public const PAYMENT_INTENT_TYPE = 'adoption';

    public static function callback(WP_REST_Request $request): \WP_REST_Response
    {
        $payload = json_decode($request->get_body(), false, 512, JSON_THROW_ON_ERROR);
        if ($payload === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        try {
            DoctrineService::getEntityManager()->beginTransaction();

            $mapper = new JsonMapper();
            $mapper->bExceptionOnMissingData = true;
            $mapper->postMappingMethod = 'afterMapping';
            /** @var AdoptionModel $adoptionModel */
            $adoptionModel = $mapper->map($payload, new AdoptionModel());

            $adoptionEntity = AdoptionService::createAdoption($adoptionModel);
            $uuid = $adoptionEntity->getUuid();

            // Dans le cas d'un paiement par virement bancaire, on exit.
            if($adoptionModel->getPaymentMethod() === PaymentMethod::BANK_TRANSFER) {
                BankTransferPayment::sendEvent($adoptionEntity);
                DoctrineService::getEntityManager()->commit();

                return APIManagement::APIOk(["uuid" => $uuid]);
            }

            $paymentIntent = AdoptionService::createInvoiceAndGetPaymentIntent($adoptionModel);

            // Add Order id to paymentintent
            StripeService::addMetadataToPaymentIntent($paymentIntent, [
                'adoption_uuid' => $uuid,
                'type' => self::PAYMENT_INTENT_TYPE
            ]);

            DoctrineService::getEntityManager()->commit();

            return APIManagement::APIOk([
                "uuid" => $uuid,
                "clientSecret" => $paymentIntent->client_secret
            ]);
        } catch (\Exception $exception) {
            DoctrineService::getEntityManager()->rollback();

            return APIManagement::APIError($exception->getMessage(), 400);
        }
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
        return "adoption";
    }
}
