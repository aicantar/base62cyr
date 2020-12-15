<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Util;

use Aicantar\Base62Cyr\Util\UnicodeString;
use Aicantar\Base62Cyr\Util\UnicodeStringUtil;
use PHPUnit\Framework\TestCase;

class UnicodeStringUtilTest extends TestCase
{
    public function consistsOfInputsProvider(): array
    {
        return [
            [
                // alphabet
                'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ',
                // input
                'проверка',
                // isValid
                true,
                // invalidChars
                ''
            ],
            [
                'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ',
                'СтрокаПосложнЕЕ',
                true,
                ''
            ],
            [
                'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ',
                'abcdef',
                false,
                'abcdef'
            ],
            [
                'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ',
                'йцукен123qwerty',
                false,
                '123qwerty'
            ]
        ];
    }

    public function testCountCharacters(): void
    {
        $emptyString = new UnicodeString('');
        $fullyUniqueString = new UnicodeString('абвгде');
        $repeatingCharactersString = new UnicodeString('кккккк');
        $repeatingPairsString = new UnicodeString('кекеке');

        // empty string
        $emptyStringFreqMap = UnicodeStringUtil::countCharacters($emptyString);
        $this->assertCount(0, array_keys($emptyStringFreqMap));
        $this->assertEquals([], $emptyStringFreqMap);

        // fully unique string
        $fullyUniqueStringFreqMap = UnicodeStringUtil::countCharacters($fullyUniqueString);
        $this->assertCount(6, array_keys($fullyUniqueStringFreqMap));
        $this->assertEquals(
            [
                'а' => 1,
                'б' => 1,
                'в' => 1,
                'г' => 1,
                'д' => 1,
                'е' => 1
            ],
            $fullyUniqueStringFreqMap
        );

        // repeating characters string
        $repeatingCharactersStringFreqMap = UnicodeStringUtil::countCharacters($repeatingCharactersString);
        $this->assertCount(1, array_keys($repeatingCharactersStringFreqMap));
        $this->assertEquals(
            [
                'к' => 6
            ],
            $repeatingCharactersStringFreqMap
        );

        // repeating character pairs string
        $repeatingPairsStringFreqMap = UnicodeStringUtil::countCharacters($repeatingPairsString);
        $this->assertCount(2, array_keys($repeatingPairsStringFreqMap));
        $this->assertEquals(
            [
                'к' => 3,
                'е' => 3,
            ],
            $repeatingPairsStringFreqMap
        );
    }

    /**
     * @dataProvider consistsOfInputsProvider
     */
    public function testConsistsOf(string $alphabet, string $input, bool $isValid, string $invalidChars)
    {
        $alphabet = new UnicodeString($alphabet);
        $subject = new UnicodeString($input);
        $returnedInvalidChars = '';

        $this->assertEquals($isValid, UnicodeStringUtil::consistsOf($alphabet, $subject, $returnedInvalidChars));
        $this->assertEquals($invalidChars, $returnedInvalidChars);
    }
}