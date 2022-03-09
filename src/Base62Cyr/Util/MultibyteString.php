<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Util;

use InvalidArgumentException;
use OutOfRangeException;
use RuntimeException;

/**
 * Simple multibyte string wrapper.
 *
 * @package Aicantar\Base62Cyr
 */
class MultibyteString
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $byteLength;

    /**
     * Construct new UnicodeString from the given raw string data.
     *
     * @param string $raw Raw string data
     */
    public function __construct(string $raw)
    {
        $this->data = $raw;
        $this->length = mb_strlen($raw);
        $this->byteLength = mb_strlen($raw, "8bit");
    }

    /**
     * Get string length in characters.
     *
     * @return int Character length
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Get string length in bytes.
     *
     * @return int Byte length
     */
    public function getByteLength(): int
    {
        return $this->byteLength;
    }

    /**
     * Get raw string data.
     *
     * @return string Raw string data
     */
    public function getRaw(): string
    {
        return $this->data;
    }

    /**
     * Replace using regular expressions. Uses preg_replace internally. Appends "u" to the pattern modifier list if not
     * provided.
     *
     * @param string $pattern
     * @param string $replacement
     *
     * @return MultibyteString
     *@throws RuntimeException
     *
     * @throws InvalidArgumentException
     * @see preg_replace()
     *
     */
    public function replace(string $pattern, string $replacement): MultibyteString
    {
        $delimiter = $pattern[0];

        if (!preg_match("/[^[:alnum:]]/u", $delimiter)) {
            throw new InvalidArgumentException(
                "Pattern must be enclosed by non-alphanumeric delimiters. The given pattern delimiter is invalid: {$delimiter}."
            );
        }

        $matches = [];
        $modifiersPattern = "/\\" . $delimiter . "([a-zA-Z]+)$/";
        $hasModifiers = preg_match($modifiersPattern, $pattern, $matches);

        if ($hasModifiers) {
            list (, $modifiers) = $matches;

            if (strpos($modifiers, "u") === false) {
                $newModifiers = $delimiter . ($modifiers . "u");
                $pattern = preg_replace($modifiersPattern, $newModifiers, $pattern);
            }
        } else {
            $pattern .= "u";
        }

        $newData = preg_replace($pattern, $replacement, $this->data);

        if ($newData === null) {
            throw new RuntimeException("PREG error: " . $this->translatePregError(preg_last_error()));
        }

        return new MultibyteString($newData);
    }

    /**
     * Returns whether the string contains the given substring.
     *
     * @param string $substring
     *
     * @return bool
     */
    public function contains(string $substring): bool
    {
        return !empty($substring) && mb_strpos($this->data, $substring) !== false;
    }

    /**
     * Return the index of the given character in the string. Returns -1 if the character is not found.
     *
     * @param string $character
     *
     * @return int
     */
    public function indexOf(string $character): int
    {
        if ($character === '') {
            return -1;
        }

        $index = mb_strpos($this->data, $character);

        if ($index === false) {
            return -1;
        }

        return $index;
    }

    /**
     * Get character at the given index.
     *
     * @param int $index
     *
     * @throws OutOfRangeException
     * @return string
     */
    public function getCharAt(int $index): string
    {
        if ($index >= $this->length) {
            throw new OutOfRangeException(
                "Index {$index} is greater than the string length. The string length is {$this->length}."
            );
        } else if ($index < 0) {
            throw new OutOfRangeException(
                "Index can't be less than 0. The given index is equal to {$index}."
            );
        }

        return mb_substr($this->data, $index, 1);
    }

    /**
     * Convert this string to an array of characters.
     *
     * @return string[]
     */
    public function asCharArray(): array
    {
        if (!$this->length) {
            return [];
        }

        return array_map(function ($index) {
            return mb_substr($this->data, $index, 1);
        }, range(0, $this->length - 1));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->data;
    }

    /**
     * Transform PREG error ID to its PHP constant name.
     *
     * @param int $error Error ID
     *
     * @return string
     */
    protected function translatePregError(int $error): string
    {
        switch ($error) {
            case PREG_NO_ERROR:
                return "PREG_NO_ERROR";
            case PREG_INTERNAL_ERROR:
                return "PREG_INTERNAL_ERROR";
            case PREG_BACKTRACK_LIMIT_ERROR:
                return "PREG_BACKTRACK_LIMIT_ERROR";
            case PREG_RECURSION_LIMIT_ERROR:
                return "PREG_RECURSION_LIMIT_ERROR";
            case PREG_BAD_UTF8_ERROR:
                return "PREG_BAD_UTF8_ERROR";
            case PREG_BAD_UTF8_OFFSET_ERROR:
                return "PREG_BAD_UTF8_OFFSET_ERROR";
            case PREG_JIT_STACKLIMIT_ERROR:
                return "PREG_JIT_STACKLIMIT_ERROR";
        }

        return "unknown error";
    }
}