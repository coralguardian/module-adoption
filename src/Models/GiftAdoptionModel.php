<?php

namespace D4rk0snet\Adoption\Models;

class GiftAdoptionModel extends AdoptionModel
{
    /**
     * @required
     */
    private array $friends;

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
}
