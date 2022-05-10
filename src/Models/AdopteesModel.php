<?php

namespace D4rk0snet\Adoption\Models;

class AdopteesModel
{
    /**
     * @required
     */
    private string $adoptionUuid;

    /**
     * @required
     */
    private array $names;

    /**
     * @return string
     */
    public function getAdoptionUuid(): string
    {
        return $this->adoptionUuid;
    }

    /**
     * @param string $adoptionUuid
     * @return AdopteesModel
     */
    public function setAdoptionUuid(string $adoptionUuid): AdopteesModel
    {
        $this->adoptionUuid = $adoptionUuid;
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
}