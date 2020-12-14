<?php declare(strict_types=1);

namespace Aicantar\Base62Cyr\Util;

use OutOfRangeException;

/**
 * Simple unicode string wrapper.
 *
 * @package Aicantar\Base62Cyr
 */
class UnicodeString
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
        $this->byteLength = mb_strlen($raw, '8bit');
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
     * Replace using regular expressions. Uses mb_ereg_replace internally.
     *
     * @param string $pattern
     * @param string $replacement
     * @param string $options
     *
     * @see mb_ereg_replace()
     *
     * @return UnicodeString
     */
    public function replace(string $pattern, string $replacement, string $options = 'msr'): UnicodeString
    {
        $patternTrimmed = trim($pattern, '/');

        return new UnicodeString(mb_ereg_replace($patternTrimmed, $replacement, $this->data, $options));
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->data;
    }
}