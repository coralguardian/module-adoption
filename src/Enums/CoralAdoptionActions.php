<?php

namespace D4rk0snet\Adoption\Enums;

enum CoralAdoptionActions : string
{
    case PENDING_ADOPTION = 'coraladoption_pending_adoption';
    case PENDING_GIFT_ADOPTION = 'coraladoption_pending_gift_adoption';
    case NEW_ADOPTION = 'coraladoption_new_adoption';
    case NEW_GIFT_ADOPTION = 'coraladoption_new_gift_adoption';
}