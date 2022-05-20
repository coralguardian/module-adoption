<?php

namespace D4rk0snet\Adoption\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @ORM\Table(name="adoption_friend")
 */
class Friend
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $sendOn;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $message = null;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\GiftAdoption", inversedBy="friends")
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private GiftAdoption $giftAdoption;

    public function __construct(string       $friendFirstname,
                                string       $friendLastname,
                                string       $friendEmail,
                                ?DateTime    $sendOn,
                                ?string      $message,
                                GiftAdoption $giftAdoption)
    {
        $this->friendFirstname = $friendFirstname;
        $this->friendLastname = $friendLastname;
        $this->friendEmail = $friendEmail;
        $this->sendOn = $sendOn;
        $this->message = $message;
        $this->giftAdoption = $giftAdoption;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getFriendFirstname(): string
    {
        return $this->friendFirstname;
    }

    public function setFriendFirstname(string $friendFirstname): Friend
    {
        $this->friendFirstname = $friendFirstname;
        return $this;
    }

    public function getFriendLastname(): string
    {
        return $this->friendLastname;
    }

    public function setFriendLastname(string $friendLastname): Friend
    {
        $this->friendLastname = $friendLastname;
        return $this;
    }

    public function getFriendEmail(): string
    {
        return $this->friendEmail;
    }

    public function setFriendEmail(string $friendEmail): Friend
    {
        $this->friendEmail = $friendEmail;
        return $this;
    }

    public function getSendOn(): ?DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(?DateTime $sendOn): Friend
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Friend
    {
        $this->message = $message;
        return $this;
    }

    public function getGiftAdoption(): GiftAdoption
    {
        return $this->giftAdoption;
    }
}