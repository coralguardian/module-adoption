<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"regularAdoption" = "AdoptionEntity", "GiftAdoption" = "GiftAdoption"})
 * @ORM\Table(name="adoption")
 */
class AdoptionEntity
{
    /**
     * @Id
     * @Column(type="uuid_binary_ordered_time", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Coralguardian\Entity\CustomerEntity")
     */
    private CustomerEntity $customer;

    /**
     * @ORM\Column(type="string", enumType="\D4rk0snet\Adoption\Enums\AdoptedProduct")
     */
    private AdoptedProduct $adoptedProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $orderDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $stripePaymentIntentId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $bankTransferRef;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @ORM\Column(type="string", enumType="\D4rk0snet\Coralguardian\Enums\Language")
     */
    private Language $lang;

    public function __construct(
        CustomerEntity $customer,
        AdoptedProduct $adoptedProduct,
        int $quantity,
        DateTime $orderDate,
        int $amount,
        Language $lang
    ) {
        $this->adoptedProduct = $adoptedProduct;
        $this->quantity = $quantity;
        $this->orderDate = $orderDate;
        $this->amount = $amount;
        $this->lang = $lang;
    }

    public function getCustomer(): CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(CustomerEntity $customer): AdoptionEntity
    {
        $this->customer = $customer;
        return $this;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    public function getStripePaymentIntentId(): ?string
    {
        return $this->stripePaymentIntentId;
    }

    public function getBankTransferRef(): ?string
    {
        return $this->bankTransferRef;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAdoptedProduct(): AdoptedProduct
    {
        return $this->adoptedProduct;
    }

    public function setStripePaymentIntentId(?string $stripePaymentIntentId): self
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;

        return $this;
    }

    public function setBankTransferRef(?string $bankTransferRef): self
    {
        $this->bankTransferRef = $bankTransferRef;

        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }
}
