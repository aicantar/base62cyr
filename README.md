# base62cyr

Base62 converter but it's Cyrillic symbols everywhere.

Converts arbitrary data to the base62 encoding, but the resulting string is encoded using Cyrillic letters
instead of Latin. Can be used to convert data into proper base62 (i.e. with Latin alphabet).

Work in progress.

### Usage

```php
require 'vendor/autoload.php';

use Aicantar\Base62Cyr\Base62Cyr;

$base62 = new Base62Cyr();

$encoded = $base62->encode("Hello, world!");        // -> 1wJfrzvdbthTq5ANZB
$decoded = $base62->decode($encoded);               // -> Hello, world!

// or, with Cyrillic alphabet

$base62cyr = new Base62Cyr(Base62Cyr::ALPHABET_CYR);

$encodedCyr = $base62cyr->encode("Hello, world!");  // -> бЫтЙХЯЩЗЁЧЛюФейцДк
$decodedCyr = $base62->decode($encoded);            // -> Hello, world!
```

#### Using different alphabet

Alphabet to use for encoding can be passed to the `alphabet` parameter of the `Base62Cyr` constructor:

```php
new Base62Cyr(Base62Cyr::ALPHABET_CYR_REVERSED);
```

`alphabet` is a string that must contain exactly 62 unique symbols. Two alphabets are included:

```php
/**
 * Cyrillic alphabet, lowercase first
 */
const ALPHABET_CYR = 'абвгдеёжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';

/**
 * Cyrillic alphabet, uppercase first
 */
const ALPHABET_CYR_REVERSED = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщыэюя';

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
```

### TODO list

- [ ] GMP base converter
- [ ] bcmath base converter
- [x] Proper support for normal base62 encoding using Latin alphabet
- [x] General refactoring

### Mentions

Heavily based on Mika Tuupola's [tuupola/base62][0] library. Make sure to check it out.

[0]: https://github.com/tuupola/base62