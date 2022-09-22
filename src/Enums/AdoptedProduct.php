<?php

namespace D4rk0snet\Adoption\Enums;

use D4rk0snet\CoralOrder\Enums\Project;

enum AdoptedProduct: string
{
    case CORAL = 'coral';
    case BUTTERFLY_REEF = 'reef.butterfly';
    case REEF_LADY = 'reef.lady';
    case REEF_NAPOLEON = 'reef.napoleon';

    public static function getAllAdoptedProduct(Project $project) : array
    {
        switch ($project) {
            case Project::INDONESIA :
                return [
                    'Corail' => self::CORAL->value,
                    'Récif papillon' => self::BUTTERFLY_REEF->value,
                    'Récif mademoiselle' => self::REEF_LADY->value,
                    'Récif napoléon' =>  self::REEF_NAPOLEON->value
                ];
            case Project::SPAIN:
                return [
                    'Corail' => self::CORAL->value
                ];
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }
    }

    public static function getRandomizedProductImages(self $self, Project $project)
    {
        $pictures = $self->getProductImages($project);
        shuffle($pictures);

        return $pictures;
    }

    public function getProductImages(Project $project): array
    {
        switch ($project) {
            case Project::INDONESIA :
                return match ($this) {
                    self::CORAL => [
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
                    self::BUTTERFLY_REEF,
                    self::REEF_LADY,
                    self::REEF_NAPOLEON => [
                        "1.jpg",
                        "2.jpg",
                        "3.JPG",
                        "4.jpg",
                        "5.jpg",
                        "6.jpg",
                    ]
                };
                // @todo: mettre les bonnes images
            case Project::SPAIN:
                return match ($this) {
                    self::CORAL => [
                        "Dendrophyllia_ramea_25.png",
                        "Dendrophyllia_ramea_29.png",
                        "Dendrophyllia_ramea_30.png",
                        "Dendrophyllia_ramea_39.png",
                        "Dendrophyllia_ramea_40.png"
                    ]
                };
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }

    }
}