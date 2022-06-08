<?php

namespace D4rk0snet\Adoption\Models;

use DateTime;

class GiftAdoptionMessageModel
{
    private ?DateTime $sendOn = null;

    private ?string $message = null;

    private bool $sendToFriend = true;

    public function getSendOn(): ?DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(?DateTime $sendOn): GiftAdoptionMessageModel
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): GiftAdoptionMessageModel
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
