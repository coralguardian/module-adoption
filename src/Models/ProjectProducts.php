<?php

namespace D4rk0snet\Adoption\Models;

class ProjectProducts implements \JsonSerializable
{
    private string $key;
    private int $price;
    private ?string $variant;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): ProjectProducts
    {
        $this->key = $key;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): ProjectProducts
    {
        $this->price = $price;
        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(?string $variant): ProjectProducts
    {
        $this->variant = $variant;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "key" => $this->getKey(),
            "price" => $this->getPrice(),
            "variant" => $this->getVariant()
        ];
    }
}