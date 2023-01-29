# PHPNext

A PHP routing system for [Next.js static HTML](https://nextjs.org/docs/advanced-features/static-html-export) without any Node.js in back-end

## Installation

```bash
composer require gaucho/phpnext
```

## Use

```php
<?php
require 'vendor/autoload.php';

use gaucho\PHPNext;

$outDir=realpath(__DIR__.'/out');
$pn=new PHPNext($outDir);
$pn->print();
```

