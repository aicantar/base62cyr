<?php declare(strict_types=1);

namespace Aicantar\Base62cyr\Tests\Encoder;

use Aicantar\Base62Cyr\Converter\SimpleConverter;
use Aicantar\Base62Cyr\Encoder\Base62Encoder;
use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Util\UnicodeString;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Base62EncoderTest extends TestCase
{
    public function encoderProvider(): array
    {
        $alphabet = new UnicodeString('абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ');

        return [
            [
                new Base62Encoder(
                    new SimpleConverter(),
                    new Base62CyrTranslator($alphabet)
                )
            ]
        ];
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeEmptyMessages(Base62Encoder $encoder): void
    {
        $this->assertEmpty($encoder->encode(''));
        $this->assertEmpty($encoder->decode(''));
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeRandomMessages(Base62Encoder $encoder): void
    {
        $data = random_bytes(512);
        $encodedData = $encoder->encode($data);
        $decodedData = $encoder->decode($encodedData);

        $this->assertEquals($data, $decodedData);
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeStringsWithLeadingZeroBytes(Base62Encoder $encoder): void
    {
        $data = [
            // single zero byte
            "\x00",
            // multiple zero bytes
            "\x00\x00\x00\x00",
            // single zero byte prefix
            "\x00\x0a\xaa\xbb",
            // multiple zero bytes prefix
            "\x00\x00\x00\x00\x0a\xaa\xbb",
            // byte string with leading zero bytes
            hex2bin('018ac7ffcbef3a')
        ];

        foreach ($data as $entry) {
            $this->assertEquals($entry, $encoder->decode($encoder->encode($entry)));
        }
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldEncodeAndDecodeIntegers(Base62Encoder $encoder): void
    {
        $data = [
            // small integer
            123,
            // big integer
            PHP_INT_MAX
        ];

        foreach ($data as $entry) {
            $this->assertEquals($entry, $encoder->decodeInteger($encoder->encodeInteger($entry)));
        }
    }

    /**
     * @dataProvider encoderProvider
     */
    public function testShouldThrowExceptionOnInvalidData(Base62Encoder $encoder): void
    {
        $this->expectException(InvalidArgumentException::class);

        $encoder->decode(random_bytes(128));
    }
}