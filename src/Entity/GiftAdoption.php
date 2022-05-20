<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\Donation\Enums\PaymentMethod;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @ORM\Table(name="adoption_gift")
 */
class GiftAdoption extends AdoptionEntity
{
    /**
     * @ORM\OneToMany(targetEntity="\D4rk0snet\Adoption\Entity\Friend", mappedBy="giftAdoption")
     */
    private Collection $friends;

    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int $quantity,
        PaymentMethod $paymentMethod,
        bool          $isPaid,
    ) {
        parent::__construct(
            customer: $customer,
            date: $date,
            amount: $amount,
            lang: $lang,
            adoptedProduct: $adoptedProduct,
            quantity: $quantity,
            paymentMethod: $paymentMethod,
            isPaid: $isPaid
        );

        $this->friends = new ArrayCollection();
    }

    public function getFriends(): Collection|ArrayCollection
    {
        return $this->friends;
    }
}
