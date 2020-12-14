<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Translator;

use Aicantar\Base62Cyr\Util\UnicodeString;

/**
 * Translators are used to translate an array of integers in a particular number system to a string of characters. This
 * interface defines methods to be implemented by all translators.
 *
 * @package Aicantar\Base62Cyr\Translator
 */
interface TranslatorInterface
{
    /**
     * Translate the given message.
     *
     * @param int[] $message
     *
     * @return string
     */
    public function translate(array $message): string;

    /**
     * Get translator alphabet.
     *
     * @return UnicodeString
     */
    public function getAlphabet(): UnicodeString;
}