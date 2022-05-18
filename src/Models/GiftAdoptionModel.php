<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Adoption\Enums\Language;
use DateTime;
use Exception;

class GiftAdoptionModel
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

    /**
     * @required
     */
    private Language $lang;

    /**
     * @required
     */
    private string $friendFirstname;

    /**
     * @required
     */
    private string $friendLastname;

    /**
     * @required
     */
    private string $friendAddress;

    /**
     * @required
     */
    private string $friendCity;

    /**
     * @required
     */
    private string $friendEmail;

    /**
     * @required
     */
    private DateTime $sendOn;

    private string $message;


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

    public function setFirstname(string $firstname): GiftAdoptionModel
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): GiftAdoptionModel
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setAddress(string $address): GiftAdoptionModel
    {
        $this->address = $address;
        return $this;
    }

    public function setCity(string $city): GiftAdoptionModel
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry(string $country): GiftAdoptionModel
    {
        $this->country = $country;
        return $this;
    }

    public function setAdoptedProduct(string $adoptedProduct): GiftAdoptionModel
    {
        $this->adoptedProduct = AdoptedProduct::from($adoptedProduct);
        return $this;
    }

    public function setQuantity(int $quantity): GiftAdoptionModel
    {
        if ($quantity < 1) {
            throw new Exception("Quantity can not be less than 1");
        }
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): GiftAdoptionModel
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

    public function setEmail(string $email): GiftAdoptionModel
    {
        $this->email = $email;
        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }

    public function setLang(string $lang): GiftAdoptionModel
    {

        try {
            $this->lang = Language::from($lang);
            return $this;
        } catch (\ValueError $exception) {
            throw new Exception("Invalid lang value");
        }
    }

    public function getFriendFirstname(): string
    {
        return $this->friendFirstname;
    }

    public function setFriendFirstname(string $friendFirstname): GiftAdoptionModel
    {
        $this->friendFirstname = $friendFirstname;
        return $this;
    }

    public function getFriendLastname(): string
    {
        return $this->friendLastname;
    }

    public function setFriendLastname(string $friendLastname): GiftAdoptionModel
    {
        $this->friendLastname = $friendLastname;
        return $this;
    }

    public function getFriendAddress(): string
    {
        return $this->friendAddress;
    }

    public function setFriendAddress(string $friendAddress): GiftAdoptionModel
    {
        $this->friendAddress = $friendAddress;
        return $this;
    }

    public function getFriendCity(): string
    {
        return $this->friendCity;
    }

    public function setFriendCity(string $friendCity): GiftAdoptionModel
    {
        $this->friendCity = $friendCity;
        return $this;
    }

    public function getFriendEmail(): string
    {
        return $this->friendEmail;
    }

    public function setFriendEmail(string $friendEmail): GiftAdoptionModel
    {
        $this->friendEmail = $friendEmail;
        return $this;
    }

    public function getSendOn(): DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(DateTime $sendOn): GiftAdoptionModel
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): GiftAdoptionModel
    {
        $this->message = $message;
        return $this;
    }
}
