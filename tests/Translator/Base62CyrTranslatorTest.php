<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Translator;

use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Util\MultibyteString;
use InvalidArgumentException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

class Base62CyrTranslatorTest extends TestCase
{
    protected $base62cyrAlphabet;

    protected function setUp(): void
    {
        $this->base62cyrAlphabet = new MultibyteString('абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ');
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

        $emptyAlphabet = new MultibyteString('');
        $shortAlphabet = new MultibyteString('абвгд');
        $repeatingAlphabet = new MultibyteString('аааааааааааааааааааааааааааааааААААААААААААААААААААААААААААААА');

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

    /**
     * @dataProvider messageProvider
     */
    public function testUntranslateConvertsTranslatedStringsBackToOriginalMessages(array $message): void
    {
        $translator = new Base62CyrTranslator($this->base62cyrAlphabet);

        $this->assertEquals($message, $translator->untranslate($translator->translate($message)));
    }

    public function testUntranslateThrowsExceptionForInvalidMessages()
    {
        $this->expectException(OutOfRangeException::class);

        $translator = new Base62CyrTranslator($this->base62cyrAlphabet);
        $translator->untranslate('{&@*#qwerty');
    }

    public function testGetAlphabetReturnsUnicodeString(): void
    {
        $translator = new Base62CyrTranslator($this->base62cyrAlphabet);

        $this->assertInstanceOf(MultibyteString::class, $translator->getAlphabet());
    }
}