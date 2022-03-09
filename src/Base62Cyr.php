<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr;

use Aicantar\Base62Cyr\Encoder\AbstractEncoder;
use Aicantar\Base62Cyr\Encoder\SimpleEncoder;
use Aicantar\Base62Cyr\Translator\Base62CyrTranslator;
use Aicantar\Base62Cyr\Translator\TranslatorInterface;
use Aicantar\Base62Cyr\Util\MultibyteString;

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
    const ALPHABET_CYR = 'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';

    /**
     * Cyrillic alphabet, uppercase first
     */
    const ALPHABET_CYR_REVERSED = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщыэюя';

    /**
     * Cyrillic alphabet based on Belarusian, lowercase first.
     */
    const ALPHABET_CYR_BEL = 'абвгдежзийклмнопрстуўфхцчшщыэюяАБВГДЕЖЗИЙКЛМНОРСТУЎФХЦЧШЩЫЭЮЯ';

    /**
     * Cyrillic alphabet based on Belarusian, uppercase first.
     */
    const ALPHABET_CYR_BEL_REVERSED = 'АБВГДЕЖЗИЙКЛМНОРСТУЎФХЦЧШЩЫЭЮЯабвгдежзийклмнопрстуўфхцчшщыэюя';

    /**
     * Cyrillic alphabet based on Ukrainian, lowercase first
     */
    const ALPHABET_CYR_UKR = 'абвгдеєжзиіїйклмнопрстуфхцчшщюяАБВГДЕЄЖЗИІЇЙКЛМНОПРСТУФХЦЧШЩЮЯ';

    /**
     * Cyrillic alphabet based on Ukrainian, uppercase first
     */
    const ALPHABET_CYR_UKR_REVERSED = 'АБВГДЕЄЖЗИІЇЙКЛМНОПРСТУФХЦЧШЩЮЯабвгдеєжзиіїйклмнопрстуфхцчшщюя';

    /**
     * GMP alphabet
     */
    const ALPHABET_GMP = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * Reversed GMP alphabet
     */
    const ALPHABET_GMP_REVERSED = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var AbstractEncoder
     */
    protected $encoder;

    /**
     * Base62 Converter constructor.
     *
     * @param string $alphabet Alphabet to use for conversion
     */
    public function __construct(string $alphabet = self::ALPHABET_GMP)
    {
        $this->translator = new Base62CyrTranslator(new MultibyteString($alphabet));
        $this->encoder = $this->getEncoder();
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
     * @return MultibyteString
     */
    public function getAlphabet(): MultibyteString
    {
        return $this->translator->getAlphabet();
    }

    /**
     * Get the best available encoder for the host PHP installation.
     *
     * @return AbstractEncoder
     */
    protected function getEncoder(): AbstractEncoder
    {
        // TODO: implement & return GMP converter if GMP is available
        return new SimpleEncoder($this->translator);
    }
}