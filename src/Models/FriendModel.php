<?php

namespace D4rk0snet\Adoption\Models;

use DateTime;

class FriendModel
{
    /**
     * @required
     */
    private string $friendFirstname;

    /**
     * @required
     */
    private string $friendLastname;

    /**
     * @required
     */
    private string $friendEmail;

    /**
     * @required
     */
    private DateTime $sendOn;

    private ?string $message = null;

    public function getFriendFirstname(): string
    {
        return $this->friendFirstname;
    }

    public function setFriendFirstname(string $friendFirstname): FriendModel
    {
        $this->friendFirstname = $friendFirstname;
        return $this;
    }

    public function getFriendLastname(): string
    {
        return $this->friendLastname;
    }

    public function setFriendLastname(string $friendLastname): FriendModel
    {
        $this->friendLastname = $friendLastname;
        return $this;
    }

    public function getFriendEmail(): string
    {
        return $this->friendEmail;
    }

    public function setFriendEmail(string $friendEmail): FriendModel
    {
        $this->friendEmail = $friendEmail;
        return $this;
    }

    public function getSendOn(): DateTime
    {
        return $this->sendOn;
    }

    public function setSendOn(DateTime $sendOn): FriendModel
    {
        $this->sendOn = $sendOn;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): FriendModel
    {
        $this->message = $message;
        return $this;
    }
}