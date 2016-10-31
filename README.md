# nia porter

The nia porter gives you the ability to port the nia framework components from PHP 7.0 to PHP 5.6.

## Installation

To build your own nia-porter:
- clone the repository
- run `composer update`
- run `bin/compile` to build `nia-porter`
- chmod +x `bin/nia-porter`
- `sudo cp bin/nia-porter /usr/local/bin/nia-porter`

**-- or --**

Just download the latest release:
- `wget https://raw.githubusercontent.com/nia-php/nia-porter/master/bin/nia-porter`
- `chmod +x nia-porter`
- `sudo mv bin/nia-porter /usr/local/bin/nia-porter`

## Usage
Run the porter on a nia component by passing the path to the directory you want to port:

```
$ nia-porter vendor/nia/sanitizing/
```

This will generate a similar output:
```
nia-porter by Patrick Ullmann and contributors.
---
processing: vendor/nia/sanitizing/sources/ClosureSanitizer.php ... [okay]
processing: vendor/nia/sanitizing/sources/TrimSanitizer.php ... [okay]
processing: vendor/nia/sanitizing/sources/NonWhitespaceSanitizer.php ... [okay]
…

---
Successfully (58 files: 52 successfully, 0 failures, 6 ignored)
```

If the status is `successfully`, the porting process is done.

## How it works
By passing the directory which contains the nia components, the application ports most of the PHP 7.0 features (like null coalesce operator) into a PHP 5.6 pendant. After that the application runs the PHP internal syntax check.

_notice: **do not** run the application on your development files because it will overwrite existing files - create a copy of your development files or run this tool only on your deployable files!_

_notice: This tool is only purposed to port the nia components into PHP 5.6 and not entire applications. Be careful if you port your own PHP 7.0 code into PHP 5.6 and run your unit tests after the porting process._

### Supported Features
* Null coalesce operator (`$value = $var ?? 123;`)
* Primitive type hints (`function foobar(string $string) { /* ... */ }`)
* Return type hints (`function foobar(string $string): bool { return true; }`)
* Anonymous classes (`new class() { /* ... */ };`)
* Strict type declare (`declare(strict_types = 1);`)

### Not Supported Features
* Combined Comparison (Spaceship) Operator (`$left <=> $right`)
* PHP/HTML/JS/CSS mixed files (those one can be broken after porting)
* `Closure::call`
* Unicode Codepoint Escape Syntax (`\u{xxxxx}`)

## Tests
To run the unit test of this application use the following command:

    $ cd /path/to/nia/porter/
    $ phpunit --bootstrap=vendor/autoload.php tests/
