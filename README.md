Parse: A PHP Security Scanner
=================

The Parse scanner searches the given PHP files for potential security-related issues.

### Usage:

```php

<?php

require_once 'vendor/autoload.php';

$target = __DIR__.'/parsedir';

$scan = new \Psecio\Parse\Scanner($target);

// Give the "paths" to match against
$matches = array(
    // Find a class method(s) in a class under a namespace
    'type:stmt.namespace>type:stmt.class>type:stmt.classMethod',

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

**PLEASE NOTE:** This tool is still in a very early stage. The work continues...