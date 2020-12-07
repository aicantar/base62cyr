<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Util;

use PHPUnit\Framework\TestCase;
use Aicantar\Base62Cyr\Util\UnicodeString;
use OutOfRangeException;

class UnicodeStringTest extends TestCase
{
    const STRING = 'проверочная строка';

    public function testWrapsStringCorrectly(): void
    {
        $unicodeString = new UnicodeString(self::STRING);

        $this->assertEquals(self::STRING, $unicodeString->getRaw());
    }

    public function testDeterminesLengthCorrectly(): void
    {
        $unicodeString = new UnicodeString(self::STRING);

        $this->assertEquals(mb_strlen(self::STRING), $unicodeString->getLength());
    }

    public function testCharAtReturnsCorrectCharacter(): void
    {
        $unicodeString = new UnicodeString(self::STRING);

        $this->assertEquals('п', $unicodeString->getCharAt(0));
        $this->assertEquals(' ', $unicodeString->getCharAt(11));
        $this->assertEquals('а', $unicodeString->getCharAt($unicodeString->getLength() - 1));
    }

    public function testCharAtThrowsOutOfRangeExceptionWhenIndexIsGreaterThanStringLength(): void
    {
        $this->expectException(OutOfRangeException::class);

        $unicodeString = new UnicodeString(self::STRING);
        $unicodeString->getCharAt($unicodeString->getLength() + 1);
    }

    public function testCharAtThrowsOutOfRangeExceptionWhenIndexIsLessThanZero(): void
    {
        $this->expectException(OutOfRangeException::class);

        $unicodeString = new UnicodeString(self::STRING);
        $unicodeString->getCharAt(-1);
    }
}