gendiff
==========

[![Maintainability](https://api.codeclimate.com/v1/badges/02a6d91c9b316936e5ef/maintainability)](https://codeclimate.com/github/fidilly/php-project-lvl2/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/02a6d91c9b316936e5ef/test_coverage)](https://codeclimate.com/github/fidilly/php-project-lvl2/test_coverage) [![Build Status](https://travis-ci.org/fidilly/php-project-lvl2.svg?branch=master)](https://travis-ci.org/fidilly/php-project-lvl2)

Gendiff is a library for comparing json or yaml file formats. This library calculates and shows the difference between files.

Installation
--------------------------

Gendiff can be installed globally with ***Composer*** or included in a project or library.

#### Global installation:

```
composer global require fidilly/gendiff
```

**example:**

[![asciicast](https://asciinema.org/a/253507.svg)](https://asciinema.org/a/253507)

#### Include:

```
composer require fidilly/gendiff
```
```
use function Differ\Gendiff\gendiff;

gendiff($pathToFile1, $pathToFile2, $format);
```

Usage via terminal
----------------

**Help call examples:**

```
gendiff -h
```
```
gendiff --help
```

Gendiff supports multiple output formats.

**examples:**
```
gendiff <file1> <file2> ###default 'pretty' format
gendiff --format pretty <file1> <file2>
gendiff --format plain <file1> <file2>
gendiff --format json <file1> <file2>
```

Usage examples
----------------------
[![asciicast](https://asciinema.org/a/254077.svg)](https://asciinema.org/a/254077)

[![asciicast](https://asciinema.org/a/254076.svg)](https://asciinema.org/a/254076)

[![asciicast](https://asciinema.org/a/254289.svg)](https://asciinema.org/a/254289)

[![asciicast](https://asciinema.org/a/254412.svg)](https://asciinema.org/a/254412)

[![asciicast](https://asciinema.org/a/254596.svg)](https://asciinema.org/a/254596)