Parse: A PHP Security Scanner
=================

The Parse scanner searches the given PHP files for potential security-related issues.

**PLEASE NOTE:** This tool is still in a very early stage. The work continues...

### Usage:

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
    'type:expr.eval'

    // Ensure that if "extract" is called, it has two params
    'func:extract[args=2]'
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
