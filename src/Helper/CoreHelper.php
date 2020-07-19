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
                    $rootKey = $converter->denormalize($outterKey);
                    $childKey = $converter->denormalize($innerKey);

                    $data[$rootKey][$childKey] = $innerValue;
                });
            } else {
                $rootKey = $converter->denormalize($outterKey);
                $data[$rootKey] = $outterValue;
            }
        });

        return $data;
    }

    /**
     * Convert array of objects into array of array.
     */
    public static function objectsToArray(array $data): array
    {
        return array_map(function ($item) {
            return $item->toArray();
        }, $data);
    }
}
