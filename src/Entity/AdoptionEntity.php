<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\CoralCustomer\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use D4rk0snet\CoralOrder\Enums\Project;
use D4rk0snet\Donation\Entity\DonationEntity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $customAmount;

    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int            $quantity,
        PaymentMethod  $paymentMethod,
        bool           $isPaid,
        Project        $project,
        ?int           $customAmount = null,
        string         $address,
        string         $postalCode,
        string         $city,
        string         $country,
        string         $firstName,
        string         $lastName
    )
    {
        parent::__construct(
            customer: $customer,
            date: $date,
            amount: $amount,
            lang: $lang,
            isPaid: $isPaid,
            paymentMethod: $paymentMethod,
            project: $project,
            address: $address,
            postalCode: $postalCode,
            city: $city,
            country: $country,
            firstName: $firstName,
            lastName: $lastName
        );
        $this->adoptedProduct = $adoptedProduct;
        $this->quantity = $quantity;
        $this->adoptees = new ArrayCollection();
        $this->customAmount = $customAmount;
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

    public function getCustomAmount(): ?int
    {
        return $this->customAmount;
    }
}
