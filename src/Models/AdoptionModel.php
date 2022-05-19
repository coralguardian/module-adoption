<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Enums\Language;
use Exception;

class AdoptionModel
{
    /**
     * @required
     */
    private string $customerUUID;

    /**
     * @required
     */
    private AdoptedProduct $adoptedProduct;

    /**
     * @required
     */
    private int $quantity;

    /**
     * @required
     */
    private float $amount;

    /**
     * @required
     */
    private Language $lang;

    public function getAdoptedProduct(): AdoptedProduct
    {
        return $this->adoptedProduct;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAdoptedProduct(string $adoptedProduct): AdoptionModel
    {
        $this->adoptedProduct = AdoptedProduct::from($adoptedProduct);
        return $this;
    }

    public function setQuantity(int $quantity): AdoptionModel
    {
        if ($quantity < 1) {
            throw new Exception("Quantity can not be less than 1");
        }
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): AdoptionModel
    {
        if ($amount < $this->getAdoptedProduct()->getProductPrice() * $this->getQuantity()) {
            throw new Exception("Price is below the product price");
        }
        $this->amount = $amount;
        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }

    public function setLang(string $lang): AdoptionModel
    {
        try {
            $this->lang = Language::from($lang);
            return $this;
        } catch (\ValueError $exception) {
            throw new Exception("Invalid lang value");
        }
    }

    public function getCustomerUUID(): string
    {
        return $this->customerUUID;
    }

    public function setCustomerUUID(string $customerUUID): AdoptionModel
    {
        $this->customerUUID = $customerUUID;
        return $this;
    }
}
