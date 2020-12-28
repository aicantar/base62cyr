<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Encoder;

use Aicantar\Base62Cyr\Translator\TranslatorInterface;
use Aicantar\Base62Cyr\Util\UnicodeString;
use Aicantar\Base62Cyr\Util\UnicodeStringUtil;
use RuntimeException;

/**
 * Abstract Base62 encoder. All custom Base62 encoders must extend this class.
 *
 * @package Aicantar\Base62Cyr\Encoder
 */
abstract class AbstractEncoder
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * AbstractEncoder constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Encode the given message into a Base62 string.
     *
     * @param string $message Message to encode
     *
     * @return string
     */
    public function encode(string $message): string
    {
        if ($message === '') {
            return '';
        }

        $messageSplit = array_map('ord', str_split($message));
        $leadingZeros = 0;

        while (!empty($messageSplit) && $messageSplit[0] === 0) {
            $leadingZeros++;
            array_shift($messageSplit);
        }

        $messageConverted = $this->convert($messageSplit, 62, 256);

        if ($leadingZeros > 0) {
            $messageConverted = array_merge(array_fill(0, $leadingZeros, 0), $messageConverted);
        }

        return $this->translator->translate($messageConverted);
    }

    /**
     * Decode Base62 string.
     *
     * @param string $messageEncoded Base62 string to decode
     *
     * @return string
     */
    public function decode(string $messageEncoded): string
    {
        if ($messageEncoded === '') {
            return '';
        }

        $this->validateBase62String($messageEncoded);

        $messageRaw = $this->translator->untranslate($messageEncoded);

        $leadingZeroes = 0;

        while (!empty($messageRaw) && $messageRaw[0] === 0) {
            $leadingZeroes++;
            array_shift($messageRaw);
        }

        $messageConverted = $this->convert($messageRaw, 256, 62);

        if ($leadingZeroes > 0) {
            $messageConverted = array_merge(array_fill(0, $leadingZeroes, 0), $messageConverted);
        }

        return implode('', array_map('chr', $messageConverted));
    }

    /**
     * Encode integer into a Base62 string.
     *
     * @param int|null $integer
     *
     * @return string
     */
    public function encodeInteger(?int $integer): string
    {
        return $this->translator->translate($this->convert([$integer], 62, 256));
    }

    /**
     * Decode encoded integer.
     *
     * @param string $integerEncoded Base62-encoded integer
     *
     * @return int|null Returns null if an empty string was provided
     */
    public function decodeInteger(string $integerEncoded): ?int
    {
        if ($integerEncoded === '') {
            return null;
        }

        $integerRaw = $this->translator->untranslate($integerEncoded);

        return (int) implode('', $this->convert($integerRaw, 10, 62));
    }

    /**
     * Throws RuntimeException if the given message is not a valid Base62 string from the provided translator's point
     * of view.
     *
     * @param string $message
     *
     * @throws RuntimeException
     */
    protected function validateBase62String(string $message)
    {
        if (!UnicodeStringUtil::consistsOf($this->translator->getAlphabet(), new UnicodeString($message))) {
            throw new RuntimeException("The provided message contains invalid symbols.");
        }
    }

    /**
     * Convert a block of integers between arbitrary bases.
     *
     * @param int[] $message Block of integers to convert
     * @param int $targetBase Target base
     * @param int $sourceBase Source base
     *
     * @return int[]
     */
    abstract protected function convert(array $message, int $targetBase, int $sourceBase): array;
}