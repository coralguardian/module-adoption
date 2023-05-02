<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Coralguardian\Event\GiftCodeSent;
use D4rk0snet\Coralguardian\Event\RecipientDone;
use D4rk0snet\CoralOrder\Model\FriendModel;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use JsonMapper;
use WP_REST_Request;
use WP_REST_Response;

class AddFriendToGiftAdoption extends APIEnpointAbstract
{
    public const GIFT_ADOPTION_UUID_PARAM = "adoptionUuid";

    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $modelArray = json_decode($request->get_body(), false, 512, JSON_THROW_ON_ERROR);
        if ($modelArray === null) {
            return APIManagement::APIError("Invalid body content", 400);
        }

        $friendModelArray = $modelArray->friends;
        $adoptionUuid = $request->get_param(self::GIFT_ADOPTION_UUID_PARAM);
        if ($adoptionUuid === null) {
            return APIManagement::APIError('Missing adoption uuid GET parameter', 400);
        }

        /** @var GiftAdoption $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()->getRepository(GiftAdoption::class)->find($adoptionUuid);
        if(!$adoptionEntity instanceof GiftAdoption) {
            return APIManagement::APINotFound();
        }

        if (count($friendModelArray) !== $adoptionEntity->getGiftCodes()->count()) {
            return APIManagement::APIError("Not enough friend to add", 400);
        }

        try {
            $mapper = new JsonMapper();
            $mapper->bExceptionOnMissingData = true;
            $mapper->postMappingMethod = 'afterMapping';
            $friendModelArray = $mapper->mapArray($friendModelArray, array(), FriendModel::class);

            /** @var GiftCodeEntity $giftCode */
            foreach ($adoptionEntity->getGiftCodes() as $index => $giftCode) {
                if ($giftCode->getFriend() !== null) {
                    throw new \Exception("Friends for this adoption have already been added");
                }
                $friend = $friendModelArray[$index];
                $friendEntity = new Friend(
                    friendFirstname: $friend->getFriendFirstname(),
                    friendLastname: $friend->getFriendLastname(),
                    friendEmail: $friend->getFriendEmail(),
                    giftCode: $giftCode
                );
                $giftCode->setFriend($friendEntity);

                DoctrineService::getEntityManager()->persist($friendEntity);
            }

            DoctrineService::getEntityManager()->flush();

            if(isset($modelArray->sendOn)) {
                $datetime = new \DateTime($modelArray->sendOn);
                $adoptionEntity->setSendOn($datetime);
            }

            if(isset($modelArray->message)) {
                $adoptionEntity->setMessage($modelArray->message);
            }

            if($adoptionEntity->getSendOn() === null) {
                foreach($adoptionEntity->getGiftCodes() as $giftCode) {
                    GiftCodeSent::sendEvent($giftCode, 1);
                }
            }

            RecipientDone::sendEvent($adoptionEntity);

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
        return "adoption/(?P<".self::GIFT_ADOPTION_UUID_PARAM.">[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})/friends";
    }
}