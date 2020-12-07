<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Util;

/**
 * UnicodeString utilities.
 *
 * @package Aicantar\Base62Cyr\Util
 */
class UnicodeStringUtil
{
    /**
     * Returns character frequency table from the given UnicodeString. The resulting array is a
     * [ char1 => freq1, ... ]-like map.
     *
     * @param UnicodeString $string
     *
     * @return array
     */
    static public function countCharacters(UnicodeString $string): array
    {
        $map = [];

        for ($i = 0; $i < $string->getLength(); $i++) {
            if (isset($map[$string->getCharAt($i)])) {
                $map[$string->getCharAt($i)]++;
                continue;
            }

            $map[$string->getCharAt($i)] = 1;
        }

        return $map;
    }
}