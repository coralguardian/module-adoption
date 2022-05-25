<?php

namespace D4rk0snet\Adoption\Models;

use DateTime;

class GiftAdoptionModel extends AdoptionModel
{
    private array $friends = [];

    private ?DateTime $sendOn = null;

    private ?string $message = null;

    private bool $sendToFriend = true;

    public function afterMapping()
    {
        parent::afterMapping();
    }

    /**
     * @return FriendModel[]
     */
    public function getFriends(): array
    {
        return $this->friends;
    }

    /**
     * @param FriendModel[] $friends
     * @return GiftAdoptionModel
     */
    public function setFriends(array $friends): GiftAdoptionModel
    {
        $this->friends = $friends;
        return $this;
    }
    public function getSendOn(): ?DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(?DateTime $sendOn): GiftAdoptionModel
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): GiftAdoptionModel
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param bool $sendToFriend
     */
    public function setSendToFriend(bool $sendToFriend): void
    {
        $this->sendToFriend = $sendToFriend;
    }

    /**
     * @return bool
     */
    public function isSendToFriend(): bool
    {
        return $this->sendToFriend;
    }
}
