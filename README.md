# snippets-php

Jahuty's PHP client.

```php
<?php
use Jahuty\Snippet\Snippet;

// set your API once
Snippet::key('123abc456def789ghi');
?>
<!doctype html>
<html>
<head>
    <title>My awesome example</title>
</head>
<body>
    <?= Snippet::get(123); ?>
</body>
```
