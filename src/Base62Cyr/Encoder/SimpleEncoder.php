<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Encoder;

/**
 * Simple Base62 encoder that uses standard PHP arithmetic operators for conversion. Slow, but runs everywhere.
 *
 * @package Aicantar\Base62Cyr\Encoder
 */
class SimpleEncoder extends AbstractEncoder
{
    /**
     * @inheritDoc
     *
     * @see http://codegolf.stackexchange.com/a/21672
     * @see https://github.com/tuupola/base62/blob/2.x/src/Base62/PhpEncoder.php#L47
     */
    protected function convert(array $message, int $targetBase, int $sourceBase): array
    {
        $result = [];

        while ($count = count($message)) {
            $quotient = [];
            $remainder = 0;

            for ($i = 0; $i !== $count; $i++) {
                $accumulator = $message[$i] + $remainder * $sourceBase;
                $remainder = $accumulator % $targetBase;
                $digit = intdiv($accumulator, $targetBase);

                if (count($quotient) > 0 || $digit > 0) {
                    $quotient[] = $digit;
                }
            }

            $result[] = $remainder;
            $message = $quotient;
        }

        return array_reverse($result);
    }
}