<?php

namespace D4rk0snet\Adoption\Repository;

use D4rk0snet\Certificate\Enums\CertificateState;
use Doctrine\ORM\EntityRepository;

class AdoptionRepository extends EntityRepository
{
    public function findCertificatesToGenerate()
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT adoption
            FROM \D4rk0snet\Adoption\Entity\AdoptionEntity adoption 
            JOIN \D4rk0snet\Adoption\Entity\AdopteeEntity adoptee
            WHERE adoption.isPaid = :paid 
            AND adoption.state = :state
            GROUP BY adoption.uuid
            '
        );
        $query->setParameter(':paid', true);
        $query->setParameter(':state', CertificateState::TO_GENERATE);

        return $query->getResult();
    }
}