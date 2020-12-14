<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Translator;

use Aicantar\Base62Cyr\Util\UnicodeString;
use Aicantar\Base62Cyr\Util\UnicodeStringUtil;
use InvalidArgumentException;

/**
 * Base62 Cyrillic Translator.
 *
 * @package Aicantar\Base62Cyr\Translator
 */
class Base62CyrTranslator implements TranslatorInterface
{
    /**
     * @var UnicodeString
     */
    protected $alphabet;

    /**
     * Base62CyrTranslator constructor.
     *
     * @param UnicodeString $alphabet Translator alphabet, must contain exactly 62 unique characters.
     */
    public function __construct(UnicodeString $alphabet)
    {
        $alphabetLength = $alphabet->getLength();
        $uniqueCharsCount = count(array_keys(UnicodeStringUtil::countCharacters($alphabet)));

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
}