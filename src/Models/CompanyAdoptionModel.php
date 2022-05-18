<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Enums\Language;
use Exception;

class CompanyAdoptionModel
{
    /**
     * @required
     */
    private string $companyName;

    /**
     * @required
     */
    private string $mainContactName;

    /**
     * @required
     */
    private string $address;

    /**
     * @required
     */
    private string $city;

    /**
     * @required
     */
    private string $country;

    /**
     * @required
     */
    private string $email;

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
    private int $amount;

    /**
     * @required
     */
    private Language $lang;

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

    public function getAdoptedProduct(): AdoptedProduct
    {
        return $this->adoptedProduct;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setFirstname(string $firstname): CompanyAdoptionModel
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): CompanyAdoptionModel
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setAddress(string $address): CompanyAdoptionModel
    {
        $this->address = $address;
        return $this;
    }

    public function setCity(string $city): CompanyAdoptionModel
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry(string $country): CompanyAdoptionModel
    {
        $this->country = $country;
        return $this;
    }

    public function setAdoptedProduct(string $adoptedProduct): CompanyAdoptionModel
    {
        $this->adoptedProduct = AdoptedProduct::from($adoptedProduct);
        return $this;
    }

    public function setQuantity(int $quantity): CompanyAdoptionModel
    {
        if ($quantity < 1) {
            throw new Exception("Quantity can not be less than 1");
        }
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): CompanyAdoptionModel
    {
        if ($amount < $this->getAdoptedProduct()->getProductPrice()) {
            throw new Exception("Price is below the product price");
        }
        $this->amount = $amount;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CompanyAdoptionModel
    {
        $this->email = $email;
        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }

    public function setLang(string $lang): CompanyAdoptionModel
    {

        try {
            $this->lang = Language::from($lang);
            return $this;
        } catch (\ValueError $exception) {
            throw new Exception("Invalid lang value");
        }
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getMainContactName(): string
    {
        return $this->mainContactName;
    }

    public function setCompanyName(string $companyName): CompanyAdoptionModel
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function setMainContactName(string $mainContactName): CompanyAdoptionModel
    {
        $this->mainContactName = $mainContactName;
        return $this;
    }
}
