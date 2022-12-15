<?php

namespace D4rk0snet\Adoption\Enums;

use D4rk0snet\CoralOrder\Enums\Project;

enum AdoptedProduct: string
{
    case CORAL = 'coral';
    case BUTTERFLY_REEF = 'reef.butterfly';
    case REEF_LADY = 'reef.lady';
    case REEF_NAPOLEON = 'reef.napoleon';

    public static function getAllAdoptedProduct(Project $project): array
    {
        switch ($project) {
            case Project::INDONESIA :
                return [
                    'Corail' => self::CORAL->value,
                    'Récif papillon' => self::BUTTERFLY_REEF->value,
                    'Récif mademoiselle' => self::REEF_LADY->value,
                    'Récif napoléon' => self::REEF_NAPOLEON->value
                ];
            case Project::SPAIN:
                return [
                    'Corail' => self::CORAL->value
                ];
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }
    }

    public function getProductType() : string
    {
        return match ($this) {
            self::CORAL => 'coral',
            self::BUTTERFLY_REEF, self::REEF_LADY, self::REEF_NAPOLEON => 'reef'
        };
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
                        "indonesia/PA070224_copyright Murdi.jpg",
                        "indonesia/PA070268_copyright Murdi.jpg",
                        "indonesia/PA070331_copyright Murdi.jpg",
                        "indonesia/PA070231_copyright Murdi.jpg",
                        "indonesia/PA070276_copyright Murdi.jpg",
                        "indonesia/PA070337_copyright Murdi.jpg",
                        "indonesia/PA070232_copyright Murdi.jpg",
                        "indonesia/PA070278_copyright Murdi.jpg",
                        "indonesia/PA070338_copyright Murdi.jpg",
                        "indonesia/PA070233_copyright Murdi.jpg",
                        "indonesia/PA070279_copyright Murdi.jpg",
                        "indonesia/PA070342_copyright Murdi.jpg",
                        "indonesia/PA070237_copyright Murdi.jpg",
                        "indonesia/PA070284_copyright Murdi.jpg",
                        "indonesia/PA070346_copyright Murdi.jpg",
                        "indonesia/PA070240_copyright Murdi.jpg",
                        "indonesia/PA070285_copyright Murdi.jpg",
                        "indonesia/PA070347_copyright Murdi.jpg",
                        "indonesia/PA070242_copyright Murdi.jpg",
                        "indonesia/PA070286_copyright Murdi.jpg",
                        "indonesia/PA070348_copyright Murdi.jpg",
                        "indonesia/PA070243_copyright Murdi.jpg",
                        "indonesia/PA070287_copyright Murdi.jpg",
                        "indonesia/PA070356_copyright Murdi.jpg",
                        "indonesia/PA070244_copyright Murdi.jpg",
                        "indonesia/PA070300_copyright Murdi.jpg",
                        "indonesia/PA070357_copyright Murdi.jpg",
                        "indonesia/PA070245_copyright Murdi.jpg",
                        "indonesia/PA070301_copyright Murdi.jpg",
                        "indonesia/PA070358_copyright Murdi.jpg",
                        "indonesia/PA070246_copyright Murdi.jpg",
                        "indonesia/PA070307_copyright Murdi.jpg",
                        "indonesia/PA070361_copyright Murdi.jpg",
                        "indonesia/PA070248_copyright Murdi.jpg",
                        "indonesia/PA070309_copyright Murdi.jpg",
                        "indonesia/PA070368_copyright Murdi.jpg",
                        "indonesia/PA070250_copyright Murdi.jpg",
                        "indonesia/PA070312_copyright Murdi.jpg",
                        "indonesia/PA070369_copyright Murdi.jpg",
                        "indonesia/PA070251_copyright Murdi.jpg",
                        "indonesia/PA070313_copyright Murdi.jpg",
                        "indonesia/PA070371_copyright Murdi.jpg",
                        "indonesia/PA070252_copyright Murdi.jpg",
                        "indonesia/PA070319_copyright Murdi.jpg",
                        "indonesia/PA070372_copyright Murdi.jpg",
                        "indonesia/PA070254_copyright Murdi.jpg",
                        "indonesia/PA070320_copyright Murdi.jpg",
                        "indonesia/PA070377_copyright Murdi.jpg",
                        "indonesia/PA070256_copyright Murdi.jpg",
                        "indonesia/PA070321_copyright Murdi.jpg",
                        "indonesia/PA070381_copyright Murdi.jpg",
                        "indonesia/PA070258_copyright Murdi.jpg",
                        "indonesia/PA070322_copyright Murdi.jpg",
                        "indonesia/PA070383_copyright Murdi.jpg",
                        "indonesia/PA070259_copyright Murdi.jpg",
                        "indonesia/PA070323_copyright Murdi.jpg",
                        "indonesia/PA070384_copyright Murdi.jpg",
                        "indonesia/PA070264_copyright Murdi.jpg",
                        "indonesia/PA070324_copyright Murdi.jpg",
                        "indonesia/PA070386_copyright Murdi.jpg",
                        "indonesia/PA070267_copyright Murdi.jpg",
                        "indonesia/PA070327_copyright Murdi.jpg",
                        "indonesia/PA070388_copyright Murdi.jpg",
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
                        "Dendrophyllia_ramea_4.png",
                        "Dendrophyllia_ramea_5.png",
                        "Dendrophyllia_ramea_6.png",
                        "Dendrophyllia_ramea_7.png",
                        "Dendrophyllia_ramea_9.png",
                        "Dendrophyllia_ramea_11.png",
                        "Dendrophyllia_ramea_12.png",
                        "Dendrophyllia_ramea_13.png",
                        "Dendrophyllia_ramea_14.png",
                        "Dendrophyllia_ramea_15.png",
                        "Dendrophyllia_ramea_16.png",
                        "Dendrophyllia_ramea_17.png",
                        "Dendrophyllia_ramea_18.png",
                        "Dendrophyllia_ramea_19.png",
                        "Dendrophyllia_ramea_20.png",
                        "Dendrophyllia_ramea_21.png",
                        "Dendrophyllia_ramea_22.png",
                        "Dendrophyllia_ramea_23.png",
                        "Dendrophyllia_ramea_24.png",
                        "Dendrophyllia_ramea_25.png",
                        "Dendrophyllia_ramea_26.png",
                        "Dendrophyllia_ramea_27.png",
                        "Dendrophyllia_ramea_28.png",
                        "Dendrophyllia_ramea_29.png",
                        "Dendrophyllia_ramea_30.png",
                        "Dendrophyllia_ramea_31.png",
                        "Dendrophyllia_ramea_32.png",
                        "Dendrophyllia_ramea_33.png",
                        "Dendrophyllia_ramea_34.png",
                        "Dendrophyllia_ramea_35.png",
                        "Dendrophyllia_ramea_36.png",
                        "Dendrophyllia_ramea_37.png",
                        "Dendrophyllia_ramea_38.png",
                        "Dendrophyllia_ramea_39.png",
                        "Dendrophyllia_ramea_40.png",
                        "Dendrophyllia_ramea_41.png",
                        "Dendrophyllia_ramea_42.png"
                    ]
                };
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }

    }
}