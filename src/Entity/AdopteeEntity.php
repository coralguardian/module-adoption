<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\Seeder;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
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
     * @ORM\Column(type="string")
     */
    private string $picture;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\AdoptionEntity", inversedBy="adoptees")
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private AdoptionEntity $adoption;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\GiftCode\Entity\GiftCodeEntity", inversedBy="adoptees")
     * @ORM\JoinColumn(referencedColumnName="uuid", name="giftCode")
     */
    private ?GiftCodeEntity $giftCode = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $adopteeDatetime;

    public function __construct(
        string $name,
        Seeder $seeder,
        AdoptionEntity $adoption,
        DateTime $adopteeDatetime,
        string $picture
    ) {
        $this->name = $name;
        $this->seeder = $seeder;
        $this->adoption = $adoption;
        $this->adopteeDatetime = $adopteeDatetime;
        $this->picture = $picture;
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

    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param GiftCodeEntity|null $giftCode
     * @return AdopteeEntity
     */
    public function setGiftCode(?GiftCodeEntity $giftCode): AdopteeEntity
    {
        $this->giftCode = $giftCode;
        return $this;
    }

    /**
     * @return GiftCodeEntity|null
     */
    public function getGiftCode(): ?GiftCodeEntity
    {
        return $this->giftCode;
    }
}