<?php

namespace D4rk0snet\Adoption\Models;

class CustomerModel
{
    private string $firstname;
    private string $lastname;
    private string $address;
    private string $city;
    private string $country;

    public function __construct(string $firstname,
                                string $lastname,
                                string $address,
                                string $city,
                                string $country)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
    }

}