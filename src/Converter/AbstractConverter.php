<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Converter;

/**
 * Class AbstractConverter
 * @package Aicantar\Base62Cyr\Converter
 */
abstract class AbstractConverter
{
    /**
     * Convert the given block of integers to an arbitrary base.
     *
     * @param int[] $message Block of integers to convert
     * @param int $targetBase Target number base
     * @param int $sourceBase Source number base
     *
     * @return int[]
     */
    public function convert(array $message, int $targetBase, int $sourceBase): array
    {
        $zeros = 0;

        while (!empty($message) && $message[0] === 0) {
            $zeros++;
            array_shift($message);
        }

        $converted = $this->doConvert($message, $targetBase, $sourceBase);

        if ($zeros > 0) {
            $converted = array_merge(array_fill(0, $zeros, 0), $converted);
        }

        return $converted;
    }

    /**
     * Actually convert the given block of integers to an arbitrary base.
     *
     * @param int[] $message Block of integers to convert
     * @param int $targetBase Target number base
     * @param int $sourceBase Source number base
     *
     * @return int[]
     */
    abstract protected function doConvert(array $message, int $targetBase, int $sourceBase): array;
}