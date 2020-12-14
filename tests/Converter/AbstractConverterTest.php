<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Converter;

use Aicantar\Base62cyr\Tests\Converter\Fake\FakeConverter;
use PHPUnit\Framework\TestCase;

class AbstractConverterTest extends TestCase
{
    public function testShouldHandleEmptyArrays(): void
    {
        $converter = new FakeConverter();

        $this->assertEquals([], $converter->convert([], 1, 1));
    }

    public function testShouldAddLeadingZerosAfterEncoding(): void
    {
        $converter = new FakeConverter();

        $this->assertEquals([0, 0, 0], $converter->convert([0, 0, 0], 1, 1));
        $this->assertEquals([0, 0, 1], $converter->convert([0, 0, 1], 1, 1));
        $this->assertEquals([0], $converter->convert([0], 1, 1));
    }
}