<?php

namespace D4rk0snet\Adoption\Models;

class AdopteesModel
{
    /**
     * @required
     */
    private string $stripePaymentIntentId;

    /**
     * @required
     */
    private array $names;

    private ?string $giftCode = null;

    public function getStripePaymentIntentId(): string
    {
        return $this->stripePaymentIntentId;
    }

    public function setStripePaymentIntentId(string $stripePaymentIntentId): AdopteesModel
    {
        $this->stripePaymentIntentId = $stripePaymentIntentId;
        return $this;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @param array $names
     * @return AdopteesModel
     */
    public function setNames(array $names): AdopteesModel
    {
        $this->names = $names;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGiftCode(): ?string
    {
        return $this->giftCode;
    }

    /**
     * @param string|null $giftCode
     */
    public function setGiftCode(?string $giftCode): void
    {
        $this->giftCode = $giftCode;
    }
}