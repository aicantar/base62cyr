<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Util;

use Aicantar\Base62Cyr\Util\UnicodeString;
use Aicantar\Base62Cyr\Util\UnicodeStringUtil;
use PHPUnit\Framework\TestCase;

class UnicodeStringUtilTest extends TestCase
{
    public function testCountCharacters(): void
    {
        $fullyUniqueString = new UnicodeString('абвгде');
        $repeatingCharactersString = new UnicodeString('кккккк');
        $repeatingPairsString = new UnicodeString('кекеке');

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
}