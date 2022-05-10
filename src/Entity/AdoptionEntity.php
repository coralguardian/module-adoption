<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Adoption\Enums\Language;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

/**
 * This entity records only not company order
 *
 * @Entity
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
     * @ORM\Column(type="string")
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private string $lastname;

    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    /**
     * @ORM\Column(type="string")
     */
    private string $city;

    /**
     * @ORM\Column(type="string")
     */
    private string $country;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

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
     * @ORM\Column(type="string", enumType="\D4rk0snet\Adoption\Enums\Language")
     */
    private Language $lang;


    public function __construct(string $firstname,
                                string $lastname,
                                string $address,
                                string $city,
                                string $country,
                                string $email,
                                AdoptedProduct $adoptedProduct,
                                int $quantity,
                                DateTime $orderDate,
                                int $amount,
                                Language $lang)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->email = $email;
        $this->adoptedProduct = $adoptedProduct;
        $this->quantity = $quantity;
        $this->orderDate = $orderDate;
        $this->amount = $amount;
        $this->lang = $lang;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getEmail(): string
    {
        return $this->email;
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