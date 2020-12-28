<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Util;

use Aicantar\Base62Cyr\Util\MultibyteString;
use Aicantar\Base62Cyr\Util\MultibyteStringUtil;
use PHPUnit\Framework\TestCase;

class MultibyteStringUtilTest extends TestCase
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
        $emptyString = new MultibyteString('');
        $fullyUniqueString = new MultibyteString('абвгде');
        $repeatingCharactersString = new MultibyteString('кккккк');
        $repeatingPairsString = new MultibyteString('кекеке');

        // empty string
        $emptyStringFreqMap = MultibyteStringUtil::countCharacters($emptyString);
        $this->assertCount(0, array_keys($emptyStringFreqMap));
        $this->assertEquals([], $emptyStringFreqMap);

        // fully unique string
        $fullyUniqueStringFreqMap = MultibyteStringUtil::countCharacters($fullyUniqueString);
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
        $repeatingCharactersStringFreqMap = MultibyteStringUtil::countCharacters($repeatingCharactersString);
        $this->assertCount(1, array_keys($repeatingCharactersStringFreqMap));
        $this->assertEquals(
            [
                'к' => 6
            ],
            $repeatingCharactersStringFreqMap
        );

        // repeating character pairs string
        $repeatingPairsStringFreqMap = MultibyteStringUtil::countCharacters($repeatingPairsString);
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
        $alphabet = new MultibyteString($alphabet);
        $subject = new MultibyteString($input);
        $returnedInvalidChars = '';

        $this->assertEquals($isValid, MultibyteStringUtil::consistsOf($alphabet, $subject, $returnedInvalidChars));
        $this->assertEquals($invalidChars, $returnedInvalidChars);
    }
}