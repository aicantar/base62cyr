<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Converter;

/**
 * Simple arbitrary base converter. Uses standard PHP math capabilities for conversion.
 * @package Aicantar\Base62Cyr\Converter
 */
class SimpleConverter extends AbstractConverter
{
    /**
     * @inheritDoc
     *
     * @see https://github.com/tuupola/base62/blob/2.x/src/Base62/PhpEncoder.php#L47
     * @see http://codegolf.stackexchange.com/a/21672
     */
    public function doConvert(array $message, int $targetBase, int $sourceBase): array
    {
        $result = [];

        while ($count = count($message)) {
            $quotient = [];
            $remainder = 0;

            for ($i = 0; $i !== $count; $i++) {
                $accumulator = $message[$i] + $remainder * $sourceBase;
                $remainder = $accumulator % $targetBase;
                $digit = ($accumulator - $remainder) / $targetBase;

                if (count($quotient) || $digit) {
                    $quotient[] = $digit;
                }
            }

            $result[] = $remainder;
            $message = $quotient;
        }

        return array_reverse($result);
    }
}