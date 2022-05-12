<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\Seeder;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * This entity records adoptees name
 *
 * @Entity
 * @ORM\Table(name="adoptee")
 */
class AdopteeEntity
{
    /**
     * @Id
     * @Column(type="uuid_binary_ordered_time", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $uuid;

    /** @ORM\Column(type="string") */
    private string $name;

    /**
     * @ORM\Column(type="string", enumType="\D4rk0snet\Adoption\Enums\Seeder")
     */
    private Seeder $seeder;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\AdoptionEntity")
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private AdoptionEntity $adoption;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $adopteeDatetime;

    public function __construct(string $name,
                                Seeder $seeder,
                                AdoptionEntity $adoption,
                                DateTime $adopteeDatetime)
    {
        $this->name = $name;
        $this->seeder = $seeder;
        $this->adoption = $adoption;
        $this->adopteeDatetime = $adopteeDatetime;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSeeder(): Seeder
    {
        return $this->seeder;
    }

    public function getAdoption(): AdoptionEntity
    {
        return $this->adoption;
    }

    public function getAdopteeDatetime(): DateTime
    {
        return $this->adopteeDatetime;
    }
}