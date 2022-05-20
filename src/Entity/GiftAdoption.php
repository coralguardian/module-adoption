<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\Donation\Enums\PaymentMethod;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity
 * @ORM\Table(name="adoption_gift")
 */
class GiftAdoption extends AdoptionEntity
{
    /**
     * @ORM\Column(type="string")
     */
    private string $friendFirstname;

    /**
     * @ORM\Column(type="string")
     */
    private string $friendLastname;

    /**
     * @ORM\Column(type="string")
     */
    private string $friendEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $sendOn;

    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int $quantity,
        PaymentMethod $paymentMethod,
        bool          $isPaid,
        string $friendFirstname,
        string $friendLastname,
        string $friendEmail,
        string $message,
        \DateTime $sendOn
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

        $this->friendFirstname = $friendFirstname;
        $this->friendLastname = $friendLastname;
        $this->friendEmail = $friendEmail;
        $this->message = $message;
        $this->sendOn = $sendOn;
    }

    public function getFriendFirstname(): string
    {
        return $this->friendFirstname;
    }

    public function setFriendFirstname(string $friendFirstname): GiftAdoption
    {
        $this->friendFirstname = $friendFirstname;
        return $this;
    }

    public function getFriendLastname(): string
    {
        return $this->friendLastname;
    }

    public function setFriendLastname(string $friendLastname): GiftAdoption
    {
        $this->friendLastname = $friendLastname;
        return $this;
    }

   public function getFriendEmail(): string
    {
        return $this->friendEmail;
    }

    public function setFriendEmail(string $friendEmail): GiftAdoption
    {
        $this->friendEmail = $friendEmail;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): GiftAdoption
    {
        $this->message = $message;
        return $this;
    }

    public function getSendOn(): DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(DateTime $sendOn): GiftAdoption
    {
        $this->sendOn = $sendOn;
        return $this;
    }
}
