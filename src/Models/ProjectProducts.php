<?php

namespace D4rk0snet\Adoption\Models;

class ProjectProducts implements \JsonSerializable
{
    private string $key;
    private string $project;
    private int $price;

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

    public function getProject(): string
    {
        return $this->project;
    }

    public function setProject(string $project): ProjectProducts
    {
        $this->project = $project;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "key" => $this->getKey(),
            "project" => $this->getProject(),
            "price" => $this->getPrice()
        ];
    }
}