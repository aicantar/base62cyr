# base62cyr

Base62 converter but it's Cyrillic symbols everywhere.

Converts arbitrary data to the base62 encoding, but the resulting string is encoded using Cyrillic letters
instead of Latin. Technically can be used to convert data to proper base62, but is still mostly a joke project.

Work in progress.

### Usage

```php
require 'vendor/autoload.php';

use Aicantar\Base62Cyr\Base62Cyr;

$base62cyr = new Base62Cyr();

$encoded = $base62cyr->encode("Hello, world!"); // -> бЫтЙХЯЩЗЁЧЛюФейцДк
$decoded = $base62cyr->decode($encoded);        // -> Hello, world!
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
const ALPHABET_CYR = 'абвгдейжзийклмнопрстуфхцчшщыэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';

/**
 * Cyrillic alphabet, uppercase first
 */
const ALPHABET_CYR_REVERSED = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯабвгдейжзийклмнопрстуфхцчшщыэюя';
```

### TODO list

- [ ] GMP base converter
- [ ] bcmath base converter
- [ ] Proper support for normal base62 encoding using Latin alphabet
- [ ] General refactoring

### Mentions

Heavily based on Mika Tuupola's [tuupola/base62][0] library. Make sure to check it out.

[0]: https://github.com/tuupola/base62