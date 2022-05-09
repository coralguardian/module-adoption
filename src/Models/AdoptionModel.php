<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;

// @todo: bien renforcer le modèle afin qu'il serve de pivot pour toutes les entry points.

class AdoptionModel
{
    /**
     * @required
     */
    private string $firstname;

    /**
     * @required
     */
    private string $lastname;

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

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

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

    public function setFirstname(string $firstname): AdoptionModel
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): AdoptionModel
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setAddress(string $address): AdoptionModel
    {
        $this->address = $address;
        return $this;
    }

    public function setCity(string $city): AdoptionModel
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry(string $country): AdoptionModel
    {
        $this->country = $country;
        return $this;
    }

    public function setAdoptedProduct(AdoptedProduct $adoptedProduct): AdoptionModel
    {
        $this->adoptedProduct = $adoptedProduct;
        return $this;
    }

    public function setQuantity(int $quantity): AdoptionModel
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): AdoptionModel
    {
        $this->amount = $amount;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): AdoptionModel
    {
        $this->email = $email;
        return $this;
    }

}