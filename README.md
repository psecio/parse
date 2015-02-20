Parse: A PHP Security Scanner
=============================

[![Packagist Version](https://img.shields.io/packagist/v/psecio/parse.svg?style=flat-square)](https://packagist.org/packages/psecio/parse)
[![Build Status](https://img.shields.io/travis/psecio/parse/master.svg?style=flat-square)](https://travis-ci.org/psecio/parse)

> **PLEASE NOTE:** This tool is still in a very early stage. The work continues...

The *Parse* scanner is a static scanning tool to review your PHP code for potential security-related
issues. A static scanner means that the code is not executed and tested via a web interface (that's
dynamic testing). Instead, the scanner looks through your code and checks for certain markers and notifies
you when any are found.

For example, you really shouldn't be using [eval](http://php.net/eval) in your code anywhere if you can
help it. When the scanner runs, it will parse down each of your files and look for any `eval()` calls.
If it finds any, it adds that match to the file and reports it in the results.


Installation
------------
Install as a development dependency in your project using [composer](https://getcomposer.org/):

    composer require --dev psecio/parse

The path to the installed executable may vary depending on your
[bin-dir](https://getcomposer.org/doc/04-schema.md#config) setting. With the
default value parse is located at `vendor/bin/psecio-parse`.

For a system-wide installation use:

    composer global require psecio/parse

Make sure you have `~/.composer/vendor/bin/` in your path.


Usage
-----
> **NOTE:** In version **0.6** the executable was renamed **psecio-parse**. In earlier
> versions the tool was simply named **parse**.

> **NOTE:** In version **0.4** and earlier the `--target` option was used to specify the
> project path, this is no longer supported. Use the syntax below.

To use the scanner execute it from the command line:

    psecio-parse scan /path/to/my/project

For more detailed information see the `help` and `list` commands.

    psecio-parse help scan

### Output formats

Currently console and xml output formats are available. Set format with the `--format` option.

    psecio-parse scan --format=xml /path/to/my/project 
    psecio-parse scan --format=dots /path/to/my/project

The console formats supports setting the verbosity using the `-v` or `-vv` switch.

    psecio-parse scan -vv /path/to/my/project

If your platform does not support ANSI codes, or if you want to redirect the console output
to a file, use the `--no-ansi` option.

    psecio-parse scan --no-ansi /path/to/my/project > filename

### Listing the checks

You can also get a listing of the current checks being done with the `rules` command:

    psecio-parse rules


The Checks
----------
Here's the current list of checks:

- Warn when sensitive values are committed (as defined by a variable like "username" set to a string)
- Warn when `display_errors` is enabled manually
- Avoid the use of `eval()`
- Avoid the use of `exit` or `die()`
- Avoid the use of logcial operators (ex. using `and` over `&&`)
- Avoid the use of the `ereg*` functions (now deprecated)
- Ensure that the second paramater of `extract` is set to not overwrite (*not* EXTR_OVERWRITE)
- Checking output methods (`echo`, `print`, `printf`, `print_r`, `vprintf`, `sprintf`) that use variables in their options
- Ensuring you're not using `echo` with `file_get_contents`
- Testing for the system execution functions and shell exec (backticks)
- Use of `readfile`, `readlink` and `readgzfile`
- Using `parse_str` or `mb_parse_str` (writes values to the local scope)
- Warn if a `.phps` file is found
- Using `session_regenerate_id` either without a paramater or using false
- Avoid use of `$_REQUEST` (know where your data is coming from)
- Don't use `mysql_real_escape_string`
- Avoiding use of `import_request_variables`
- Avoid use of `$GLOBALS`
- Ensure the use of type checking validating against booleans (`===`)
- Ensure that the `/e` modifier isn't used in regular expressions (execute)
- Using concatenation in `header()` calls
- Avoiding the use of $http_raw_post_data

Plenty more to come... (yup, `@todo`)


TODO
----
See the current issues list for `@todo` items...

Parse is covered under the MIT license.

@author Chris Cornutt (ccornutt@phpdeveloper.org)
