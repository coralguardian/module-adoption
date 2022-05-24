<?php

namespace D4rk0snet\Adoption\Enums;

enum AdoptedProduct: string
{
    case CORAL = 'coral';
    case BUTTERFLY_REEF = 'reef.butterfly';
    case REEF_LADY = 'reef.lady';
    case REEF_NAPOLEON = 'reef.napoleon';

    public static function getAllAdoptedProduct() : array
    {
        return [
            'Corail' => self::CORAL,
            'Récif papillon' => self::BUTTERFLY_REEF,
            'Récif mademoiselle' => self::REEF_LADY,
            'Récif napoléon' =>  self::REEF_NAPOLEON
        ];
    }

    public function getStripeProductId(): string
    {
        return match ($this) {
            AdoptedProduct::CORAL => getenv('STRIPE_MODE') === 'test' ? 'prod_LSVYXFnIStsEzY' : 'prod_LS3ht8WpyrWaCA',
            AdoptedProduct::BUTTERFLY_REEF => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqtryr8rkKhZ8' : 'prod_LS3xkDunBD8suJ',
            AdoptedProduct::REEF_LADY => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqvzKfXJGMlrO' : 'prod_LS3vPfmveu1RCt',
            AdoptedProduct::REEF_NAPOLEON => getenv('STRIPE_MODE') === 'test' ? 'prod_LVqwt7dTeM8Vwl' : 'prod_LS3ypcdG7tdxBp'
        };
    }

    public function getProductPrice(): int
    {
        return match ($this) {
            AdoptedProduct::CORAL => 30,
            AdoptedProduct::BUTTERFLY_REEF => 2250,
            AdoptedProduct::REEF_LADY => 900,
            AdoptedProduct::REEF_NAPOLEON => 4500
        };
    }

    public function getProductPriceId(): string
    {
        return match ($this) {
            AdoptedProduct::CORAL => getenv('STRIPE_MODE') === 'test' ? 'price_1KlaXDLwnTG7uFWwbEvnynJG' : 'price_1Kl9ZpLwnTG7uFWwtEBLFM5q',
            AdoptedProduct::BUTTERFLY_REEF => getenv('STRIPE_MODE') === 'test' ? 'price_1KopCBLwnTG7uFWwDaMTjQZv' : 'price_1Kl9pkLwnTG7uFWwi2NNcBng',
            AdoptedProduct::REEF_LADY => getenv('STRIPE_MODE') === 'test' ? 'price_1KopDSLwnTG7uFWwgf5Gpov4' : 'price_1Kl9niLwnTG7uFWwG3UZnUg0',
            AdoptedProduct::REEF_NAPOLEON => getenv('STRIPE_MODE') === 'test' ? 'price_1KopEyLwnTG7uFWwvXgb4N5u' : 'price_1Kl9qELwnTG7uFWwJdi6Mdts'
        };
    }

    public function getProductImages(): array
    {
        return match ($this) {
            AdoptedProduct::CORAL => [
                "P9121940.jpg",
                "P9121941.jpg",
                "P9121942.jpg",
                "P9121943.jpg",
                "P9121944.jpg",
                "P9121945.jpg",
                "P9121946.jpg",
                "P9121947.jpg",
                "P9121948.jpg",
                "P9121949.jpg",
                "P9121950.jpg",
                "P9121952.jpg",
                "P9121953.jpg",
                "P9121954.jpg",
                "P9121955.jpg",
                "P9121956.jpg",
                "P9121957.jpg",
                "P9121958.jpg",
                "P9121959.jpg",
                "P9121960.jpg",
                "P9121961.jpg",
                "P9121962.jpg",
                "P9121963.jpg",
                "P9121964.jpg",
                "P9121965.jpg",
                "P9121967.jpg",
                "P9121968.jpg",
                "P9121969.jpg",
                "P9121970.jpg",
                "P9121971.jpg",
                "P9121972.jpg",
                "P9121973.jpg",
                "P9121974.jpg",
                "P9121975.jpg",
            ],
            AdoptedProduct::BUTTERFLY_REEF,
            AdoptedProduct::REEF_LADY,
            AdoptedProduct::REEF_NAPOLEON => [
                "1.jpg",
                "2.jpg",
                "3.JPG",
                "4.jpg",
                "5.jpg",
                "6.jpg",
            ]
        };
    }
}