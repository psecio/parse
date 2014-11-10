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

If you're interested in how the matching happens in the background, here's an example of a few different
rule formats:

```php
<?php

require_once 'vendor/autoload.php';

$target = __DIR__.'/parsedir';

$scan = new \Psecio\Parse\Scanner($target);

// Give the "paths" to match against
$matches = array(
    // Find a class method(s) in a class under a namespace
    'type:stmt.namespace->type:stmt.class->type:stmt.classMethod',

    // Look for any eval calls
    'type:expr.eval',

    // Ensure that if "extract" is called, it has two params
    // 	and find ones that don't
    'func:extract[argcount{<2}]',

    // Or we can get a lot more complex:
    // 	- ensure that argument one is set (required=true)
    // 	- ensure that argument two is set (required=true)
    // 	- ensure that argument two is equal to 0 when compared as integers
    'func:extract[arg{location=1,required=true}&arg{location=2,required=true,=(integer)0}]'
);

$results = $scan->execute($matches);

echo "RESULTS:\n";
print_r($results);

?>
```

The basic idea here is to create a DSL that lets us locate patterns in the files. If the pattern is found (like an `eval`)
expression, we have a problem. The matches will be attached to the `File` instances which are returned in the `$results`
above.

For example: since using `eval` is a terrible idea, any `File` in the results that has a match of node type `Expr\Eval`
should be flagged as having a vulnerability. From here we can get the file that the issue is in and the line number(s)
off of the `Node` object to narrow down the location.

### Output

Currently there's only one method for output included with the tool - XML. You can either use the result from the `execute` method ont he `Scanner` directly, or you can pass the output to the `Output` handler:

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
