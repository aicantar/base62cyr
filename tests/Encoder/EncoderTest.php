<?php /** @noinspection PhpDocSignatureInspection */

declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Encoder;

use Aicantar\Base62Cyr\Encoder\AbstractEncoder;
use Aicantar\Base62Cyr\Encoder\SimpleEncoder;
use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Util\UnicodeString;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class EncoderTest extends TestCase
{
    const ALPHABET_CYR = 'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';

    /**
     * Encoders to be tested
     *
     * @var AbstractEncoder[]
     */
    protected $encoders;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $translatorCyr = new Base62CyrTranslator(new UnicodeString(self::ALPHABET_CYR));

        // add encoders to test here
        $this->encoders = [
            new SimpleEncoder($translatorCyr)
        ];
    }

    public function encoderProvider(): array
    {
        return array_map(function ($encoder) {
            return [$encoder];
        }, $this->encoders);
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeEmptyMessage(AbstractEncoder $encoder): void
    {
        $this->assertEmpty($encoder->encode(''));
        $this->assertEmpty($encoder->decode(''));
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeRandomMessage(AbstractEncoder $encoder): void
    {
        $message = random_bytes(512);

        $encodedMessage = $encoder->encode($message);
        $decodedMessage = $encoder->decode($encodedMessage);

        $this->assertEquals($message, $decodedMessage);
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeMessagesWithLeadingNullBytes(AbstractEncoder $encoder): void
    {
        $messages = [
            // single null byte
            "\x00",
            // multiple null bytes
            "\x00\x00\x00\x00",
            // single null byte prefix
            "\x00\x0a\xaa\xbb",
            // multiple null bytes prefix
            "\x00\x00\x00\x00\x0a\xaa\xbb",
            // byte string with leading null half-byte
            hex2bin('018ac7ffcbef3a')
        ];

        foreach ($messages as $message) {
            $this->assertEquals($message, $encoder->decode($encoder->encode($message)));
        }
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeIntegers(AbstractEncoder $encoder): void
    {
        $integers = [
            // null
            null,
            // zero
            0,
            // small integer
            123,
            // big integer
            PHP_INT_MAX
        ];

        foreach ($integers as $integer) {
            $this->assertEquals($integer, $encoder->decodeInteger($encoder->encodeInteger($integer)));
        }
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testDecodeIntegerShouldReturnNullOnEmptyMessage(AbstractEncoder $encoder): void
    {
        $this->assertNull($encoder->decodeInteger(''));
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testDecodeShouldThrowRuntimeExceptionOnInvalidData(AbstractEncoder $encoder): void
    {
        $this->expectException(RuntimeException::class);

        $encoder->decode($encoder->decode(random_bytes(128)));
    }

    /**
     * Asserts that all encoders return the same encoded value for the same message
     */
    public function testEncoderParity(): void
    {
        $message = random_bytes(64);
        $results = [];

        foreach ($this->encoders as $encoder) {
            $results[] = $encoder->encode($message);
        }

        $this->assertCount(1, array_unique($results));
    }
}