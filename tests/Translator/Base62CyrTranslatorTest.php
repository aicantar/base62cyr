<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Translator;

use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Util\UnicodeString;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Base62CyrTranslatorTest extends TestCase
{
    protected $base62cyrAlphabet;

    protected function setUp(): void
    {
        $this->base62cyrAlphabet = new UnicodeString('абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ');
    }

    public function messageProvider(): array
    {
        return [
            [
                [],
                ''
            ],
            [
                [2, 4, 6, 8],
                'вдёз'
            ],
            [
                [30, 61],
                'яЯ'
            ]
        ];
    }

    public function testShouldThrowAnExceptionForInvalidAlphabets(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $emptyAlphabet = new UnicodeString('');
        $shortAlphabet = new UnicodeString('абвгд');
        $repeatingAlphabet = new UnicodeString('аааааааааааааааааааааааааааааааААААААААААААААААААААААААААААААА');

        new Base62CyrTranslator($emptyAlphabet);
        new Base62CyrTranslator($shortAlphabet);
        new Base62CyrTranslator($repeatingAlphabet);
    }

    public function testShouldNotThrowExceptionsForValidAlphabets(): void
    {
        $translator = new Base62CyrTranslator($this->base62cyrAlphabet);

        $this->assertInstanceOf(Base62CyrTranslator::class, $translator);
    }

    /**
     * @dataProvider messageProvider
     */
    public function testTranslatesMessagesCorrectly(array $message, string $result): void
    {
        $translator = new Base62CyrTranslator($this->base62cyrAlphabet);

        $this->assertEquals($result, $translator->translate($message));
    }
}