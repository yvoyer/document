<?php declare(strict_types=1);

namespace App\Tests\Assertions;

final class TranslationsToAssertion
{
    /**
     * @param array ...$rows
     * @return array
     */
    public static function rowsToMap(array ...$rows): array {
        $map = [];
        foreach ($rows as $row) {
            $map[$row['field']][$row['locale']] = $row['content'];
        }

        return $map;
    }
}
