<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Util;

/**
 * MultibyteString utilities.
 *
 * @package Aicantar\Base62Cyr\Util
 */
class MultibyteStringUtil
{
    /**
     * Returns character frequency table from the given UnicodeString. The resulting array is a
     * [ char1 => freq1, ... ]-like map.
     *
     * @param MultibyteString $string
     *
     * @return array
     */
    static public function countCharacters(MultibyteString $string): array
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

    /**
     * Test whether the given subject consists only from the characters found in the given alphabet. Optionally pass the
     * invalid characters to the string referenced by the invalidChars parameter.
     *
     * @param MultibyteString $alphabet
     * @param MultibyteString $subject
     * @param string|null &$invalidChars
     *
     * @return bool
     */
    static public function consistsOf(
        MultibyteString $alphabet,
        MultibyteString $subject,
        string &$invalidChars = null
    ): bool {
        $pattern = '/[' . $alphabet->getRaw() . ']+/';
        $remainingCharacters = $subject->replace($pattern, '');

        if ($remainingCharacters->getLength()) {
            if ($invalidChars !== null) {
                $invalidChars = $remainingCharacters->getRaw();
            }

            return false;
        }

        return true;
    }
}