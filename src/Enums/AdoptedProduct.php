<?php

namespace D4rk0snet\Adoption\Enums;

enum AdoptedProduct : string
{
    case CORAL = 'coral';
    case BUTTERFLY_REEF = 'butterfly_reef';
    case REEF_LADY = 'reef_lady';
    case REEF_NAPOLEON = 'reef_napoleon';

    public function getStripeProductId() : string
    {
        return match($this) {
            AdoptedProduct::CORAL => 'prod_LS3ht8WpyrWaCA',
            AdoptedProduct::BUTTERFLY_REEF => 'prod_LS3xkDunBD8suJ',
            AdoptedProduct::REEF_LADY => 'prod_LS3vPfmveu1RCt',
            AdoptedProduct::REEF_NAPOLEON => 'prod_LS3ypcdG7tdxBp'
        };
    }

    public function getProductPrice() : int
    {
        return match($this) {
            AdoptedProduct::CORAL => 30,
            AdoptedProduct::BUTTERFLY_REEF => 2250,
            AdoptedProduct::REEF_LADY => 900,
            AdoptedProduct::REEF_NAPOLEON => 4500
        };
    }

    public function getProductPriceId() : string
    {
        return match($this) {
            AdoptedProduct::CORAL => 'price_1Kl9ZpLwnTG7uFWwtEBLFM5q',
            AdoptedProduct::BUTTERFLY_REEF => 'price_1Kl9pkLwnTG7uFWwi2NNcBng',
            AdoptedProduct::REEF_LADY => 'price_1Kl9niLwnTG7uFWwG3UZnUg0',
            AdoptedProduct::REEF_NAPOLEON => 'price_1Kl9qELwnTG7uFWwJdi6Mdts'
        };
    }
}