<?php

namespace D4rk0snet\Adoption\Command;

use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Repository\FriendRepository;
use D4rk0snet\Email\Event\OwnerScheduledCodeSentNotificationEvent;
use D4rk0snet\Email\Event\ScheduledGiftCodeSent;
use Hyperion\Doctrine\Service\DoctrineService;
use WP_CLI;

class SendFutureGift
{
    public static function runCommand()
    {
        WP_CLI::log("== Lancement du script d'envoi des codes cadeaux ==\n");

        /** @var FriendRepository $repository */
        $repository = DoctrineService::getEntityManager()->getRepository(Friend::class);

        $friendsToSendGiftTo = $repository->getAllGiftAdoptionToDealWithToday();
        if(count($friendsToSendGiftTo) === 0) {
            return WP_CLI::success("Aucun code à envoyer aujourd'hui");
        }

        foreach($friendsToSendGiftTo as $friend)
        {
            ScheduledGiftCodeSent::send(
                email: $friend->getFriendEmail(),
                lang: $friend->getGiftAdoption()->getLang(),
                product: $friend->getGiftAdoption()->getAdoptedProduct(),
                message: $friend->getMessage(),
                giftCode: $friend->getGiftCode(),
                friendName: $friend->getFriendFirstname() . " " . $friend->getFriendLastname(),
                quantity: $friend->getGiftAdoption()->getQuantity()
            );

            OwnerScheduledCodeSentNotificationEvent::send(
                email: $friend->getGiftAdoption()->getCustomer()->getEmail(),
                lang: $friend->getGiftAdoption()->getLang(),
                quantity: $friend->getGiftAdoption()->getQuantity()
            );

            WP_CLI::log("=> Code cadeau de la commande de ".$friend->getGiftAdoption()->getCustomer()->getEmail()." envoyé.");
        }
        WP_CLI::log("");

        return WP_CLI::success("Fin de l'envoi des codes cadeaux");
    }
}