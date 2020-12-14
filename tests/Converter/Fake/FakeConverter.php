<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Converter\Fake;

use Aicantar\Base62Cyr\Converter\AbstractConverter;

/**
 * Fake converter that does no base conversion. Used for testing.
 *
 * @package Aicantar\Base62cyr\Tests\Converter\Fake
 */
class FakeConverter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    protected function doConvert(array $message, int $targetBase, int $sourceBase): array
    {
        return $message;
    }
}