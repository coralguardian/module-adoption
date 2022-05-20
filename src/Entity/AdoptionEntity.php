<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\Donation\Entity\DonationEntity;
use D4rk0snet\Donation\Enums\PaymentMethod;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;

/**
 * @Entity
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"regularAdoption" = "AdoptionEntity", "GiftAdoption" = "GiftAdoption"})
 * @ORM\Table(name="adoption")
 */
class AdoptionEntity extends DonationEntity
{
    /**
     * @ORM\Column(type="string", enumType="\D4rk0snet\Adoption\Enums\AdoptedProduct")
     */
    private AdoptedProduct $adoptedProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\OneToMany(mappedBy="adoption", targetEntity="\D4rk0snet\Adoption\Entity\AdopteeEntity")
     */
    private Collection $adoptees;


    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int            $quantity,
        PaymentMethod  $paymentMethod,
        bool           $isPaid
    ) {
        parent::__construct(
            customer: $customer,
            date: $date,
            amount: $amount,
            lang: $lang,
            isPaid: $isPaid,
            paymentMethod: $paymentMethod
        );
        $this->adoptedProduct = $adoptedProduct;
        $this->quantity = $quantity;
        $this->adoptees = new ArrayCollection();
    }

    public function getAdoptedProduct(): AdoptedProduct
    {
        return $this->adoptedProduct;
    }

    public function setAdoptedProduct(AdoptedProduct $adoptedProduct): AdoptionEntity
    {
        $this->adoptedProduct = $adoptedProduct;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): AdoptionEntity
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getAdoptees(): ArrayCollection|Collection
    {
        return $this->adoptees;
    }
}
