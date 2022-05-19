<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\Donation\Entity\DonationEntity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
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


    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int $quantity
    ) {
        parent::__construct(
            customer: $customer,
            date: $date,
            amount: $amount,
            lang: $lang
        );
        $this->adoptedProduct = $adoptedProduct;
        $this->quantity = $quantity;
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
}
