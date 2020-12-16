<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Encoder;

use Aicantar\Base62Cyr\Converter\AbstractConverter;
use Aicantar\Base62Cyr\Translator\TranslatorInterface;
use Aicantar\Base62Cyr\Util\UnicodeString;
use Aicantar\Base62Cyr\Util\UnicodeStringUtil;
use InvalidArgumentException;

/**
 * Encodes random messages into the base62 encoding.
 *
 * @package Aicantar\Base62Cyr\Encoder
 */
class Base62Encoder
{
    /**
     * @var AbstractConverter
     */
    protected $converter;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Base62Encoder constructor.
     *
     * @param AbstractConverter $converter Arbitrary base converter
     * @param TranslatorInterface $translator Translator to transform the encoded message into a string of characters
     */
    public function __construct(AbstractConverter $converter, TranslatorInterface $translator)
    {
        $this->converter = $converter;
        $this->translator = $translator;
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
        if (empty($message)) {
            return '';
        }

        $messageRaw = array_map('ord', str_split($message));
        $messageConverted = $this->converter->convert($messageRaw, 62, 256);

        return $this->translator->translate($messageConverted);
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
        if (empty($value)) {
            return '';
        }

        $invalidChars = '';

        if (!UnicodeStringUtil::consistsOf(
            $this->translator->getAlphabet(),
            new UnicodeString($value),
            $invalidChars
        )) {
            throw new InvalidArgumentException(
                "Invalid message. The message contains the following invalid characters: \"{$invalidChars}\"."
            );
        }

        $valueRaw = $this->translator->untranslate($value);

        $valueConverted = $this->converter->convert($valueRaw, 256, 62);

        return implode('', array_map('chr', $valueConverted));
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
        $encodedValue = $this->converter->convert([$value], 62, 256);

        return $this->translator->translate($encodedValue);
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
        $invalidChars = '';

        if (!UnicodeStringUtil::consistsOf(
            $this->translator->getAlphabet(),
            new UnicodeString($value),
            $invalidChars
        )) {
            throw new InvalidArgumentException(
                "Invalid message. The message contains the following invalid characters: \"{$invalidChars}\"."
            );
        }

        $valueRaw = $this->translator->untranslate($value);
        $valueConverted = $this->converter->convert($valueRaw, 10, 62);

        return (int) implode('', $valueConverted);
    }
}