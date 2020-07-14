<?php
namespace App\Helper;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class CoreHelper
{
    /**
     * Denormalize data array.
     */
    public static function denormalize(array $toNormalize): array
    {
        $data = [];
        $converter = new CamelCaseToSnakeCaseNameConverter(null, true);
        
        array_walk($toNormalize, function ($outterValue, $outterKey) use ($converter, &$data) {
            if (is_array($outterValue)) {
                array_walk($outterValue, function ($innerValue, $innerKey) use ($converter, &$data, $outterKey) {
                    $data["{$converter->denormalize($outterKey)}"]["{$converter->denormalize($innerKey)}"] = $innerValue;
                });
            } else {
                $data["{$converter->denormalize($outterKey)}"] = $outterValue;
            }
        });

        return $data;
    }
}
