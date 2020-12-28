<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Util;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Aicantar\Base62Cyr\Util\MultibyteString;
use OutOfRangeException;

class MultibyteStringTest extends TestCase
{
    const STRING = 'проверочная строка';

    public function testWrapsStringCorrectly(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertEquals(self::STRING, $unicodeString->getRaw());
    }

    public function testDeterminesLengthCorrectly(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertEquals(mb_strlen(self::STRING), $unicodeString->getLength());
    }

    public function testCharAtReturnsCorrectCharacter(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertEquals('п', $unicodeString->getCharAt(0));
        $this->assertEquals(' ', $unicodeString->getCharAt(11));
        $this->assertEquals('а', $unicodeString->getCharAt($unicodeString->getLength() - 1));
    }

    public function testCharAtThrowsOutOfRangeExceptionWhenIndexIsGreaterThanStringLength(): void
    {
        $this->expectException(OutOfRangeException::class);

        $unicodeString = new MultibyteString(self::STRING);
        $unicodeString->getCharAt($unicodeString->getLength() + 1);
    }

    public function testCharAtThrowsOutOfRangeExceptionWhenIndexIsLessThanZero(): void
    {
        $this->expectException(OutOfRangeException::class);

        $unicodeString = new MultibyteString(self::STRING);
        $unicodeString->getCharAt(-1);
    }

    public function testContainsShouldReturnTrueForValidSubstrings(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertTrue($unicodeString->contains('прове'));
        $this->assertTrue($unicodeString->contains('вер'));
        $this->assertTrue($unicodeString->contains('ая стр'));
        $this->assertTrue($unicodeString->contains('ока'));
    }

    public function testContainsShouldReturnFalseForInvalidSubstrings(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertFalse($unicodeString->contains(''));
        $this->assertFalse($unicodeString->contains('аа'));
        $this->assertFalse($unicodeString->contains('бббб'));
        $this->assertFalse($unicodeString->contains('1234'));
    }

    public function testReplaceWorksAndReturnsNewUnicodeString(): void
    {
        $unicodeString = new MultibyteString(self::STRING);
        $newString = $unicodeString->replace('/проверочная/', 'новая');

        $this->assertInstanceOf(MultibyteString::class, $newString);
        $this->assertNotSame($unicodeString, $newString);
        $this->assertEquals('новая строка', $newString->getRaw());
        $this->assertEquals('проверочная строка', $unicodeString->getRaw());
    }

    public function testReplaceThrowsExceptionOnInvalidDelimiters(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $unicodeString = new MultibyteString(self::STRING);
        $unicodeString->replace('проверочная', 'новая');
    }

    public function testReplaceHonorsPatternModifiers(): void
    {
        $unicodeString = new MultibyteString(self::STRING);
        $newString = $unicodeString->replace('/ПрОвЕрОчНаЯ/i', 'новая');

        $this->assertEquals('новая строка', $newString->getRaw());
    }

    public function testIndexOfReturnsValidIndexForExistingCharacters(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $index0 = $unicodeString->indexOf('п');
        $index1 = $unicodeString->indexOf('я');
        $index2 = $unicodeString->indexOf('к');

        $this->assertGreaterThan(-1, $index0);
        $this->assertGreaterThan(-1, $index1);
        $this->assertGreaterThan(-1, $index2);

        $this->assertEquals('п', $unicodeString->getCharAt($index0));
        $this->assertEquals('я', $unicodeString->getCharAt($index1));
        $this->assertEquals('к', $unicodeString->getCharAt($index2));
    }

    public function testIndexOfShouldReturnInvalidIndexForEmptyArgument(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertEquals(-1, $unicodeString->indexOf(''));
    }

    public function testAsCharArrayReturnsEmptyArrayForEmptyString(): void
    {
        $unicodeString = new MultibyteString('');
        $charArray = $unicodeString->asCharArray();

        $this->assertIsArray($charArray);
        $this->assertEmpty($charArray);
    }

    public function testShouldReturnRawValueWhenConvertedToString(): void
    {
        $testString = 'проверочная строка';
        $unicodeString = new MultibyteString($testString);

        $this->assertEquals($testString, (string) $unicodeString);
    }

    public function testGetByteLengthShouldReturnStringLenthInBytes(): void
    {
        $unicodeString = new MultibyteString(self::STRING);

        $this->assertEquals(strlen(self::STRING), $unicodeString->getByteLength());
    }
}