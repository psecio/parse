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
    'type:stmt.namespace>type:stmt.class>type:stmt.classMethod',
    'type:expr.eval'
);

$results = $scan->execute($matches);

echo "RESULTS:\n";
print_r($results);

?>
```

**PLEASE NOTE:** This tool is still in a very early stage. The work continues...