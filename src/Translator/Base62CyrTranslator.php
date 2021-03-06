<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Translator;

use Aicantar\Base62Cyr\Util\MultibyteString;
use Aicantar\Base62Cyr\Util\MultibyteStringUtil;
use InvalidArgumentException;
use OutOfRangeException;

/**
 * Base62 Cyrillic Translator.
 *
 * @package Aicantar\Base62Cyr\Translator
 */
class Base62CyrTranslator implements TranslatorInterface
{
    /**
     * @var MultibyteString
     */
    protected $alphabet;

    /**
     * Base62CyrTranslator constructor.
     *
     * @param MultibyteString $alphabet Translator alphabet, must contain exactly 62 unique characters.
     */
    public function __construct(MultibyteString $alphabet)
    {
        $alphabetLength = $alphabet->getLength();
        $uniqueCharsCount = count(array_keys(MultibyteStringUtil::countCharacters($alphabet)));

        if ($alphabetLength !== 62 || $uniqueCharsCount !== 62) {
            throw new InvalidArgumentException(
                "The alphabet should contain exactly 62 unique characters. The provided alphabet contains {$alphabetLength} characters, out of which {$uniqueCharsCount} are unique."
            );
        }

        $this->alphabet = $alphabet;
    }

    /**
     * @inheritDoc
     */
    public function translate(array $message): string
    {
        return implode('', array_map(function ($index) {
            return $this->alphabet->getCharAt($index);
        }, $message));
    }

    /**
     * @inheritDoc
     * @throws OutOfRangeException
     */
    public function untranslate(string $message): array
    {
        if ($message === '') {
            return [];
        }

        $messageStr = new MultibyteString($message);

        return array_map(function ($char) {
            $index = $this->alphabet->indexOf($char);

            if ($index === -1) {
                throw new OutOfRangeException("Character \"{$char}\" is not in the alphabet.");
            }

            return $index;
        }, $messageStr->asCharArray());
    }

    /**
     * @inheritDoc
     */
    public function getAlphabet(): MultibyteString
    {
        return $this->alphabet;
    }
}