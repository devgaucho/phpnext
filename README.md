# PHPNext

A PHP routing system for [Next.js static HTML](https://nextjs.org/docs/advanced-features/static-html-export) without any Node.js in back-end

## Installation

```bash
composer require devgaucho/phpnext
```

## Use

```php
<?php
require 'vendor/autoload.php';

use devgaucho\PHPNext;

$outDir=realpath(__DIR__.'/out');
$pn=new PHPNext($outDir);
$pn->print();
```

