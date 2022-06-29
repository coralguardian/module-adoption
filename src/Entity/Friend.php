<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="adoption_friend")
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary_ordered_time", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", name="firstName")
     */
    private string $friendFirstname;

    /**
     * @ORM\Column(type="string", name="lastName")
     */
    private string $friendLastname;

    /**
     * @ORM\Column(type="string", name="email")
     */
    private string $friendEmail;

    /**
     * @ORM\OneToOne (targetEntity="\D4rk0snet\GiftCode\Entity\GiftCodeEntity", inversedBy="friend")
     * @ORM\JoinColumn(name="giftCode", referencedColumnName="uuid")
     */
    private GiftCodeEntity $giftCode;

    public function __construct(
        string       $friendFirstname,
        string       $friendLastname,
        string       $friendEmail,
        GiftCodeEntity $giftCode
    )
    {
        $this->friendFirstname = $friendFirstname;
        $this->friendLastname = $friendLastname;
        $this->friendEmail = $friendEmail;
        $this->setGiftCode($giftCode);
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

    public function setGiftCode(GiftCodeEntity $giftCode): Friend
    {
        $this->giftCode = $giftCode;
        return $this;
    }

    public function getGiftCode(): GiftCodeEntity
    {
        return $this->giftCode;
    }
}