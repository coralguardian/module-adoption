<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\Donation\Enums\PaymentMethod;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @ORM\Table(name="adoption_gift")
 */
class GiftAdoption extends AdoptionEntity
{
    /**
     * @ORM\OneToMany(targetEntity="\D4rk0snet\Adoption\Entity\Friend", mappedBy="giftAdoption")
     */
    private Collection $friends;

    /**
     * @ORM\OneToMany(targetEntity="\D4rk0snet\GiftCode\Entity\GiftCodeEntity", mappedBy="giftAdoption")
     */
    private Collection $giftCodes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $sendOn;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $message = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private bool $sendToFriend;

    public function __construct(
        CustomerEntity $customer,
        DateTime       $date,
        float          $amount,
        Language       $lang,
        AdoptedProduct $adoptedProduct,
        int            $quantity,
        PaymentMethod  $paymentMethod,
        bool           $isPaid,
        bool           $sendToFriend,
        ?DateTime      $sendOn,
        ?string        $message,
    )
    {
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

        $this->friends = new ArrayCollection();
        $this->giftCodes = new ArrayCollection();
        $this->sendOn = $sendOn;
        $this->message = $message;
        $this->sendToFriend = $sendToFriend;
    }

    public function getFriends(): Collection|ArrayCollection
    {
        return $this->friends;
    }

    public function getSendOn(): ?DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(?DateTime $sendOn): GiftAdoption
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): GiftAdoption
    {
        $this->message = $message;
        return $this;
    }

    public function addGiftCode(GiftCodeEntity $giftCode): GiftAdoption
    {
        if (!$this->giftCodes->contains($giftCode)) {
            $this->giftCodes->add($giftCode);
        }
        return $this;

    }

    public function addFriend(Friend $friend): GiftAdoption
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
        }
        return $this;
    }

    public function getGiftCodes(): ArrayCollection|Collection
    {
        return $this->giftCodes;
    }

    /**
     * @return bool
     */
    public function isSendToFriend(): bool
    {
        return $this->sendToFriend;
    }
}
