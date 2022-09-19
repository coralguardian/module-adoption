<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Models\GiftAdoptionMessageModel;
use D4rk0snet\CoralCustomer\Entity\CompanyCustomerEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use JsonMapper;
use WP_REST_Request;
use WP_REST_Response;

class AddMessageToGiftAdoption extends APIEnpointAbstract
{
    public const GIFT_ADOPTION_UUID_PARAM = "adoption_uuid";

    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $modelArray = json_decode($request->get_body(), false, 512, JSON_THROW_ON_ERROR);
        if ($modelArray === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        $adoptionUuid = $request->get_param(self::GIFT_ADOPTION_UUID_PARAM);
        if ($adoptionUuid === null) {
            return APIManagement::APIError('Missing adoption uuid GET parameter', 400);
        }

        /** @var GiftAdoption $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(GiftAdoption::class)->find($adoptionUuid);
        if($adoptionEntity === null) {
            return APIManagement::APINotFound();
        }

        if (!$adoptionEntity->getCustomer() instanceof CompanyCustomerEntity) {
            return APIManagement::APIForbidden("Only adoptions by companies can add messages");
        }

        try {
            $mapper = new JsonMapper();
            $mapper->bExceptionOnMissingData = true;
            /** @var GiftAdoptionMessageModel $giftAdoptionMessageModel */
            $giftAdoptionMessageModel = $mapper->map($modelArray, new GiftAdoptionMessageModel());

            $adoptionEntity
                ->setSendOn($giftAdoptionMessageModel->getSendOn())
                ->setMessage($giftAdoptionMessageModel->getMessage())
            ;

            DoctrineService::getEntityManager()->flush();

            return APIManagement::APIOk();
        } catch(\Exception $exception) {
            return APIManagement::APIError($exception->getMessage(), 500);
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
        return "adoption/gift/message";
    }
}