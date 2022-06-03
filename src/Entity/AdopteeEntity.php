<?php

namespace D4rk0snet\Adoption\Entity;

use D4rk0snet\Adoption\Enums\Seeder;
use D4rk0snet\Certificate\Enums\CertificateState;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use DateTime;

use Doctrine\ORM\Mapping as ORM;

/**
 * This entity records adoptees name
 *
 * @ORM\Entity
 * @ORM\Table(name="adoptee")
 */
class AdopteeEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary_ordered_time", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
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
     * @ORM\JoinColumn(referencedColumnName="uuid", name="adoption")
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

    /**
     * @ORM\Column(type="string", enumType="\D4rk0snet\Certificate\Enums\CertificateState", options={"default": \D4rk0snet\Certificate\Enums\CertificateState::TO_GENERATE}))
     */
    private CertificateState $state;

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
        $this->state = CertificateState::TO_GENERATE;
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


    /**
     * @return CertificateState
     */
    public function getState(): CertificateState
    {
        return $this->state;
    }

    /**
     * @param CertificateState $state
     * @return AdopteeEntity
     */
    public function setState(CertificateState $state): AdopteeEntity
    {
        $this->state = $state;
        return $this;
    }
}