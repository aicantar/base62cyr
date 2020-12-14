<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Converter;

use Aicantar\Base62Cyr\Converter\SimpleConverter;
use PHPUnit\Framework\TestCase;

class SimpleConverterTest extends TestCase
{
    /**
     * @see https://codegolf.stackexchange.com/q/1620
     * @return array
     */
    public function conversionProvider(): array
    {
        return [
            [
                [1, 0],
                100,
                10,
                [10]
            ],
            [
                [41, 15, 156, 123, 254, 156, 141, 2, 24],
                16,
                256,
                [2, 9, 0, 15, 9, 12, 7, 11, 15, 14, 9, 12, 8, 13, 0, 2, 1, 8]
            ],
            [
                [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                10,
                2,
                [1, 2, 3, 7, 9, 4, 0, 0, 3, 9, 2, 8, 5, 3, 8, 0, 2, 7, 4, 8, 9, 9, 1, 2, 4, 2, 2, 3]
            ],
            [
                [41, 42, 43],
                36,
                256,
                [1, 21, 29, 22, 3]
            ]
        ];
    }

    public function testShouldReturnSameResultForIdenticalBases(): void
    {
        $converter = new SimpleConverter();

        $this->assertEquals([1, 2, 3], $converter->convert([1, 2, 3], 16, 16));
        $this->assertEquals([0, 1], $converter->convert([0, 1], 10, 10));
    }

    /**
     * @dataProvider conversionProvider
     */
    public function testShouldConvertMessagesCorrectly($message, $targetBase, $sourceBase, $result): void
    {
        $converter = new SimpleConverter();

        $this->assertEquals($result, $converter->convert($message, $targetBase, $sourceBase));
    }
}