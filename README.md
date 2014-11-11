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

Plenty more to come... (yup, `@todo`)

### Output

Currently there's only one method for output included with the tool - XML. You can either use the result from the `execute` method on the `Scanner` directly, or you can pass the output to the `Output` handler:

```php
<?php
$results = $scan->execute($matches);

$xml = new \Psecio\Parse\Output\Xml();
$output = $xml->generate($results);
?>
```

### TODO

1. Create the list of security checks
2. Add more evaluation functionality and operators
3. Decide on report output type (is there a standard output that makes sense?)

Parse is covered under the MIT license.

@author Chris Cornutt (ccornutt@phpdeveloper.org)
