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
            AdoptedProduct::CORAL => getenv('STRIPE_MODE') === 'test' ? 'prod_LSVYXFnIStsEzY' : 'prod_LS3ht8WpyrWaCA',
            AdoptedProduct::BUTTERFLY_REEF => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqtryr8rkKhZ8' : 'prod_LS3xkDunBD8suJ',
            AdoptedProduct::REEF_LADY => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqvzKfXJGMlrO' : 'prod_LS3vPfmveu1RCt',
            AdoptedProduct::REEF_NAPOLEON => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqwt7dTeM8Vwl' : 'prod_LS3ypcdG7tdxBp'
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
            AdoptedProduct::CORAL => getenv('STRIPE_MODE') === 'test' ? 'price_1KlaXDLwnTG7uFWwbEvnynJG' : 'price_1Kl9ZpLwnTG7uFWwtEBLFM5q',
            AdoptedProduct::BUTTERFLY_REEF => getenv('STRIPE_MODE') === 'test' ? 'price_1KopCBLwnTG7uFWwDaMTjQZv' : 'price_1Kl9pkLwnTG7uFWwi2NNcBng',
            AdoptedProduct::REEF_LADY => getenv('STRIPE_MODE') === 'test' ? 'price_1KopDSLwnTG7uFWwgf5Gpov4' : 'price_1Kl9niLwnTG7uFWwG3UZnUg0',
            AdoptedProduct::REEF_NAPOLEON => getenv('STRIPE_MODE') === 'test' ? 'price_1KopEyLwnTG7uFWwvXgb4N5u' : 'price_1Kl9qELwnTG7uFWwJdi6Mdts'
        };
    }
}