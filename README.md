Parse: A PHP Security Scanner
=================

**PLEASE NOTE:** This tool is still in a very early stage. The work continues...

### The Basics:

The *Parse* scanner is a static scanning tool to review your PHP code for potential security-related
issues. A static scanner means that the code is not executed and tested via a web interface (that's
dynamic testing). Instead, the scanner looks through your code and checks for certain markers and notifies
you when any are found.

For example, you really shouldn't be using [eval](http://php.net/eval) in your code anywhere if you can
help it. When the scanner runs, it will parse down each of your files and look for any `eval()` calls.
If it finds any, it adds that match to the file and reports it in the results.

### Installation:

You can install the tool using Composer:

```
composer require psecio/parse
```

### Usage:

To use the scanner, you can execute it from the command line:

```
bin/parse scan --target=/path/to/myproject
```

The `target` parameter is required as it tells the `parse` tool where to start.

### The Details:

Here's the current list of tests being executed:

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

### Output

Currently the tool will just output to the console in a not-so-machine-readable format. There is an XML output object defined and working, but the console command doesn't have a switch for it yet (an easy add).

### Listing the checks

You can also get a listing of the current checks being done with the `list` command:

```
bin/parse list
```

Resulting in:

```
ID  | Name                                | Description
================================================================================
0   | TestAvoidGlobalsUse                 | The use of $GLOBALS should be avoided.
1   | TestAvoidMagicConstants             | Avoid the use of magic constants like __DIR__ & __FILE__
2   | TestAvoidRequestUse                 | Avoid the use of $_REQUEST (know where your data comes fron)
3   | TestEchoWithFileGetContents         | Using `echo` with results of `file_get_contents` could lead to injection issues.
```

etc...

### TODO

See the current issues list for `@todo` items...

Parse is covered under the MIT license.

@author Chris Cornutt (ccornutt@phpdeveloper.org)
