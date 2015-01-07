Parse: A PHP Security Scanner
=============================

> **PLEASE NOTE:** This tool is still in a very early stage. The work continues...


The Basics
----------
The *Parse* scanner is a static scanning tool to review your PHP code for potential security-related
issues. A static scanner means that the code is not executed and tested via a web interface (that's
dynamic testing). Instead, the scanner looks through your code and checks for certain markers and notifies
you when any are found.

For example, you really shouldn't be using [eval](http://php.net/eval) in your code anywhere if you can
help it. When the scanner runs, it will parse down each of your files and look for any `eval()` calls.
If it finds any, it adds that match to the file and reports it in the results.


Installation
------------
Install the tool as a development dependency using [composer](https://getcomposer.org/):

```shell
composer require --dev psecio/parse
```

The path to the installed executable may vary depending on your
[bin-dir](https://getcomposer.org/doc/04-schema.md#config) setting in `composer.json`.
With the default setting execute parse using:

```shell
vendor/bin/parse scan path/to/source
```

For a system-wide installation use:

```shell
composer global require psecio/parse
```

Make sure you have `~/.composer/vendor/bin/` in your path.


Usage
-----
> **NOTE:** In earlier versions the `--target` option was used to specify the
> project path, this is no longer supported. Instead use the syntax below.

To use the scanner, you can execute it from the command line:

```shell
bin/parse scan /path/to/myproject
```

For more detailed information see the `help` and `list` commands.

```shell
bin/parse help
bin/parse list
```

### Output formats

Currently cleartext and xml output formats are available (cleartext is used
by default). Set format with the `--format` option.

```shell
bin/parse scan /path/to/myproject --format=xml
bin/parse scan /path/to/myproject --format=txt
```

The default format supports setting the verbosity using the `-v` or `-vv` switch.

```shell
bin/parse scan /path/to/myproject -vv
```

### Listing the checks

You can also get a listing of the current checks being done with the `list-tests` command:

```shell
bin/parse list-tests
```


The Details
-----------
Here's the current list of tests being executed:

- Warn when sensitive values are committed (as defined by a variable like "username" set to a string)
- Warn when `display_errors` is enabled manually
- Avoid magic constants `__DIR__` or `__FILE__`
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
- Throws exception if a `.phps` file is found
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
