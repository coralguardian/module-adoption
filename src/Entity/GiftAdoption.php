<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\CoralCustomer\Entity\CustomerEntity;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use D4rk0snet\CoralOrder\Enums\Project;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="adoption_gift")
 */
class GiftAdoption extends AdoptionEntity
{
//    /**
//     * @ORM\OneToOne(targetEntity="\D4rk0snet\Adoption\Entity\Friend", mappedBy="giftAdoption")
//     */
//    private Friend $friend;

    /**
     * @ORM\OneToMany(targetEntity="\D4rk0snet\GiftCode\Entity\GiftCodeEntity", mappedBy="giftAdoption")
     */
    private Collection $giftCodes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $sendOn;

    /**
     * @ORM\Column(type="text", nullable=true)
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
        Project        $project,
        ?DateTime      $sendOn,
        ?string        $message
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
            isPaid: $isPaid,
            project: $project
        );
        $this->giftCodes = new ArrayCollection();
        $this->sendOn = $sendOn;
        $this->message = $message;
        $this->sendToFriend = $sendToFriend;
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
