<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Translator;

use Aicantar\Base62Cyr\Util\MultibyteString;

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
     * Convert the given translated string to an array of integers representing the original message.
     *
     * @param string $message
     *
     * @return int[]
     */
    public function untranslate(string $message): array;

    /**
     * Get translator alphabet.
     *
     * @return MultibyteString
     */
    public function getAlphabet(): MultibyteString;
}