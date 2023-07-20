<?php

namespace D4rk0snet\Adoption\Models;

class AdopteesModel
{
    /**
     * @required
     */
    private array $names;
    private ?string $giftCode = null;

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