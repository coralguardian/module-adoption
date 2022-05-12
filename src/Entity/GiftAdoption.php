<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Adoption\Enums\Language;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity
 * @ORM\Table(name="gifted_adoption")
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
    private string $friendAddress;

    /**
     * @ORM\Column(type="string")
     */
    private string $friendCity;

    /**
     * @ORM\Column(type="string")
     */
    private string $friendEmail;

    /**
     * @ORM\Column(type="string")
     */
    private string $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $sendOn;

    public function __construct(string         $firstname,
                                string         $lastname,
                                string         $address,
                                string         $city,
                                string         $country,
                                string         $email,
                                AdoptedProduct $adoptedProduct,
                                int            $quantity,
                                DateTime       $orderDate,
                                int            $amount,
                                Language       $lang,
                                string $friendFirstname,
                                string $friendLastname,
                                string $friendAddress,
                                string $friendCity,
                                string $friendEmail,
                                string $message,
                                \DateTime $sendOn)
    {
        parent::__construct($firstname, $lastname, $address, $city, $country, $email, $adoptedProduct, $quantity, $orderDate, $amount, $lang);
        $this->friendFirstname = $friendFirstname;
        $this->friendLastname = $friendLastname;
        $this->friendAddress = $friendAddress;
        $this->friendCity = $friendCity;
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

    public function getFriendAddress(): string
    {
        return $this->friendAddress;
    }

    public function setFriendAddress(string $friendAddress): GiftAdoption
    {
        $this->friendAddress = $friendAddress;
        return $this;
    }

    public function getFriendCity(): string
    {
        return $this->friendCity;
    }

    public function setFriendCity(string $friendCity): GiftAdoption
    {
        $this->friendCity = $friendCity;
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