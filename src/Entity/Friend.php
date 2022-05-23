<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\GiftCode\Service\GiftCodeService;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity(repositoryClass="\D4rk0snet\Adoption\Repository\FriendRepository")
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
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\GiftAdoption", inversedBy="friends")
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private GiftAdoption $giftAdoption;

    /**
     * @ORM\Column(type="string")
     */
    private string $giftCode;


    public function __construct(string       $friendFirstname,
                                string       $friendLastname,
                                string       $friendEmail,
                                GiftAdoption $giftAdoption,
                                ?string      $giftCode
    )
    {
        $this->friendFirstname = $friendFirstname;
        $this->friendLastname = $friendLastname;
        $this->friendEmail = $friendEmail;
        $this->setGiftAdoption($giftAdoption);
        $this->giftCode = $giftCode;
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

    public function setGiftAdoption(GiftAdoption $giftAdoption): Friend
    {
        $this->giftAdoption = $giftAdoption;
        $giftAdoption->addFriend($this);
        return $this;
    }

    public function getGiftAdoption(): GiftAdoption
    {
        return $this->giftAdoption;
    }

    public function getGiftCode(): string
    {
        return $this->giftCode;
    }

    public function setGiftCode(string $giftCode): Friend
    {
        $this->giftCode = $giftCode;
        return $this;
    }
}