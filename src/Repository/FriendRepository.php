<?php

namespace D4rk0snet\Adoption\Repository;

use D4rk0snet\Adoption\Entity\Friend;
use DateTime;
use Doctrine\ORM\EntityRepository;

class FriendRepository extends EntityRepository
{
    /**
     * @return Friend[]
     */
    public function getAllGiftAdoptionToDealWithToday()
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT ga 
            FROM \D4rk0snet\Adoption\Entity\GiftAdoption ga 
            WHERE ga.sendOn = :today 
            AND WHERE ga.sendToFriend = :sendToFriend'
        );
        $query->setParameter(':today', (new DateTime())->format('Y-m-d'));
        $query->setParameter(':sendToFriend', true);

        return $query->getResult();
    }
}