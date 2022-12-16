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
                        Project::INDONESIA->value."/PA070224_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070268_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070331_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070231_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070276_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070337_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070232_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070278_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070338_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070233_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070279_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070342_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070237_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070284_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070346_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070240_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070285_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070347_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070242_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070286_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070348_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070243_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070287_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070356_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070244_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070300_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070357_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070245_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070301_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070358_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070246_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070307_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070361_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070248_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070309_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070368_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070250_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070312_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070369_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070251_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070313_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070371_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070252_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070319_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070372_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070254_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070320_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070377_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070256_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070321_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070381_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070258_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070322_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070383_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070259_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070323_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070384_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070264_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070324_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070386_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070267_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070327_copyright Murdi.jpg",
                        Project::INDONESIA->value."/PA070388_copyright Murdi.jpg",
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
                        Project::SPAIN->value."/Dendrophyllia_ramea_4.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_5.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_6.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_7.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_9.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_11.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_12.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_13.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_14.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_15.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_16.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_17.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_18.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_19.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_20.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_21.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_22.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_23.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_24.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_25.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_26.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_27.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_28.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_29.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_30.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_31.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_32.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_33.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_34.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_35.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_36.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_37.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_38.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_39.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_40.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_41.png",
                        Project::SPAIN->value."/Dendrophyllia_ramea_42.png"
                    ]
                };
            default:
                throw new \Exception("Unhandled ProjectEnum");
        }

    }
}