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

Currently console (dots), xml and json output formats are available. Set format with the `--format` option.

    psecio-parse scan --format=xml /path/to/my/project
    psecio-parse scan --format=dots /path/to/my/project
    psecio-parse scan --format=json /path/to/my/project

The console formats supports setting the verbosity using the `-v` or `-vv` switch.

    psecio-parse scan -vv /path/to/my/project

If your platform does not support ANSI codes, or if you want to redirect the console output
to a file, use the `--no-ansi` option.

    psecio-parse scan --no-ansi /path/to/my/project > filename

### Listing the checks

You can also get a listing of the current checks being done with the `rules` command:

    psecio-parse rules

### Managing rules to run

There are several ways to control which rules are run. You can specifically include rules using
the `--include-rules` option, specifically exclude them with `--exclude-rules`, turn them on and
off on a case-by-case basis using annotations, and disable annotations using
`--disable-annotations`.

#### Excluding and Including rules

By default, `psecio-parse scan` includes all available rules in its scan. By using
`--exclude-rules` and `--include-rules`, the rules included can be reduced.

Any rules specified by `--exclude-rules` are explicitly excluded from the scan, regardless of any
other options selected. These rules cannot be added back to the scan, short of re-running the scan
with different options. Invalid rules are silently ignored.

If `--include-rules` is provided, only those rules specified can be used. No other rules are
checked. Note that rules that aren't available (whether they do not exist or `--excluded-rules` is
used to exclude them) cannot be included. Invalid rules are silently ignored.

#### Annotations

Rules can be enabled and disabled using DocBlock annotations. These are comments in the code being
scanned that tells *Parse* to specifically enable or disable a rule for the block of code the
DocBlock applies to.

* `@psecio\parse\disable <rule>`: Tells *Parse* to ignore the given rule for the scope of the
  DocBlock.
* `@psecio\parse\enable <rule>`: Tells *Parse* to enable the given rule for the scope of the
  DocBlock. This can be used to re-enable a particular rule when `@psecio\parse\disable` has been
  applied to the containing scope.

Note that annotations cannot enable tests that have been omitted via the command line options. If
a test is disabled at the command line, it is disabled for the entire scan, regardless of any
annotations.

Comments can be added after `<rule>` following a dobule-slash (`//`) comment separator. It is
recommended that comments be used to indicate why the rule has been disabled or enabled.

To disable the use of annotations, use the `--disable-annotations` option.

See the `examples` directory for some examples of the use of annotations for *Parse*.

The Checks
----------
Here's the current list of checks:

- Warn when sensitive values are committed (as defined by a variable like "username" set to a string)
- Warn when `display_errors` is enabled manually
- Avoid the use of `eval()`
- Avoid the use of `exit` or `die()`
- Avoid the use of logical operators (ex. using `and` over `&&`)
- Avoid the use of the `ereg*` functions (now deprecated)
- Ensure that the second parameter of `extract` is set to not overwrite (*not* EXTR_OVERWRITE)
- Checking output methods (`echo`, `print`, `printf`, `print_r`, `vprintf`, `sprintf`) that use variables in their options
- Ensuring you're not using `echo` with `file_get_contents`
- Testing for the system execution functions and shell exec (backticks)
- Use of `readfile`, `readlink` and `readgzfile`
- Using `parse_str` or `mb_parse_str` (writes values to the local scope)
- Warn if a `.phps` file is found
- Using `session_regenerate_id` either without a parameter or using false
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
