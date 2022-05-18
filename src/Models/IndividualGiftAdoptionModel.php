<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\Coralguardian\Enums\Language;
use DateTime;
use Exception;

class IndividualGiftAdoptionModel extends IndividualAdoptionModel
{
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

    private ?string $message = null;


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

    public function setFirstname(string $firstname): IndividualGiftAdoptionModel
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): IndividualGiftAdoptionModel
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setAddress(string $address): IndividualGiftAdoptionModel
    {
        $this->address = $address;
        return $this;
    }

    public function setCity(string $city): IndividualGiftAdoptionModel
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry(string $country): IndividualGiftAdoptionModel
    {
        $this->country = $country;
        return $this;
    }

    public function setAdoptedProduct(string $adoptedProduct): IndividualGiftAdoptionModel
    {
        $this->adoptedProduct = AdoptedProduct::from($adoptedProduct);
        return $this;
    }

    public function setQuantity(int $quantity): IndividualGiftAdoptionModel
    {
        if ($quantity < 1) {
            throw new Exception("Quantity can not be less than 1");
        }
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): IndividualGiftAdoptionModel
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

    public function setEmail(string $email): IndividualGiftAdoptionModel
    {
        $this->email = $email;
        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }

    public function setLang(string $lang): IndividualGiftAdoptionModel
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

    public function setFriendFirstname(string $friendFirstname): IndividualGiftAdoptionModel
    {
        $this->friendFirstname = $friendFirstname;
        return $this;
    }

    public function getFriendLastname(): string
    {
        return $this->friendLastname;
    }

    public function setFriendLastname(string $friendLastname): IndividualGiftAdoptionModel
    {
        $this->friendLastname = $friendLastname;
        return $this;
    }

    public function getFriendAddress(): string
    {
        return $this->friendAddress;
    }

    public function setFriendAddress(string $friendAddress): IndividualGiftAdoptionModel
    {
        $this->friendAddress = $friendAddress;
        return $this;
    }

    public function getFriendCity(): string
    {
        return $this->friendCity;
    }

    public function setFriendCity(string $friendCity): IndividualGiftAdoptionModel
    {
        $this->friendCity = $friendCity;
        return $this;
    }

    public function getFriendEmail(): string
    {
        return $this->friendEmail;
    }

    public function setFriendEmail(string $friendEmail): IndividualGiftAdoptionModel
    {
        $this->friendEmail = $friendEmail;
        return $this;
    }

    public function getSendOn(): DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(DateTime $sendOn): IndividualGiftAdoptionModel
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): IndividualGiftAdoptionModel
    {
        $this->message = $message;
        return $this;
    }
}
