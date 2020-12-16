<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr;

use Aicantar\Base62Cyr\Converter\AbstractConverter;
use Aicantar\Base62Cyr\Converter\SimpleConverter;
use Aicantar\Base62Cyr\Encoder\Base62Encoder;
use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Util\UnicodeString;

/**
 * Base62 Cyrillic converter.
 *
 * @package Aicantar\Base62Cyr
 */
class Base62Cyr
{
    /**
     * Cyrillic alphabet, lowercase first
     */
    const ALPHABET_CYR = 'абвгдейжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';

    /**
     * Cyrillic alphabet, uppercase first
     */
    const ALPHABET_CYR_REVERSED = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯабвгдейжзийклмнопрстуфхцчшщыэюя';

    /**
     * @var UnicodeString
     */
    protected $alphabet;

    /**
     * @var Base62Encoder
     */
    protected $encoder;

    /**
     * Create base62 Cyrillic converter instance.
     *
     * @param string $alphabet Alphabet to use for conversion
     */
    public function __construct(string $alphabet = self::ALPHABET_CYR)
    {
        $this->alphabet = new UnicodeString($alphabet);
        $this->encoder = new Base62Encoder($this->getConverter(), new Base62CyrTranslator($this->alphabet));
    }

    /**
     * Encode the given message to a base62 string.
     *
     * @param string $message
     *
     * @return string
     */
    public function encode(string $message): string
    {
        return $this->encoder->encode($message);
    }

    /**
     * Encode the given integer to a base62 string.
     *
     * @param int $value
     *
     * @return string
     */
    public function encodeInteger(int $value): string
    {
        return $this->encoder->encodeInteger($value);
    }

    /**
     * Decode the given base62 string back to the original message.
     *
     * @param string $value
     *
     * @return string
     */
    public function decode(string $value): string
    {
        return $this->encoder->decode($value);
    }

    /**
     * Decode the given base62 string back to the original integer.
     *
     * @param string $value
     *
     * @return int
     */
    public function decodeInteger(string $value): int
    {
        return $this->encoder->decodeInteger($value);
    }

    /**
     * Get alphabet used by the converter.
     *
     * @return UnicodeString
     */
    public function getAlphabet(): UnicodeString
    {
        return $this->alphabet;
    }

    /**
     * Get best converter available for the host's PHP configuration.
     *
     * @return AbstractConverter
     */
    protected function getConverter(): AbstractConverter
    {
        // TODO: implement gmp converter and return it if it is available
        return new SimpleConverter();
    }
}